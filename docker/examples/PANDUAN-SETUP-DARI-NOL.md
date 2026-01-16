# =============================================
# PANDUAN SERVER MULTI-WEB
# =============================================
# Berisi 2 panduan:
# 1. Setup Server dari Nol
# 2. Tambah Domain/Web Baru
# =============================================

---

# BAGIAN 1: Setup Server dari Nol

## Prasyarat

- VPS/Server dengan Ubuntu/Debian
- Domain sudah pointing ke IP server
- Akses SSH ke server

---

## Step 1: Install Docker

```bash
apt update && apt upgrade -y
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
apt install docker-compose -y

# Verifikasi
docker --version
docker-compose --version
```

---

## Step 2: Clone Server Infrastructure

```bash
cd /root
git clone https://github.com/NaUzAr/server-infra.git
```

---

## Step 3: Buat Network

```bash
docker network create smartagri-network
```

---

## Step 4: Start Database

```bash
cd /root/server-infra/shared-db
docker-compose up -d
```

---

## Step 5: Clone & Start Web App Pertama

```bash
# Clone project
cd /root
git clone https://github.com/NaUzAr/iot.git
cd iot

# Build & Start
docker-compose build
docker-compose up -d

# Setup Laravel
docker exec smartagri-app cp .env.example .env
docker exec smartagri-app php artisan key:generate
docker exec smartagri-app php artisan migrate --force
docker exec smartagri-app chmod -R 775 /var/www/storage
```

---

## Step 6: Tambah Domain ke Reverse Proxy

Edit nginx config:
```bash
nano /root/server-infra/reverse-proxy/nginx.conf
```

Tambah:
```nginx
server {
    listen 80;
    server_name smartagri.web.id www.smartagri.web.id;

    location / {
        proxy_pass http://smartagri-nginx:80;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

---

## Step 7: Start Reverse Proxy

```bash
cd /root/server-infra/reverse-proxy
docker-compose up -d
```

---

## Step 8: Verifikasi

```bash
docker ps
curl -I http://smartagri.web.id
```

---

## Quick Reference - Urutan Start:

```bash
docker network create smartagri-network
cd /root/server-infra/shared-db && docker-compose up -d
cd /root/iot && docker-compose up -d
cd /root/server-infra/reverse-proxy && docker-compose up -d
```

---
---

# BAGIAN 2: Tambah Domain/Web Baru

Panduan untuk menambah web/domain baru ke server yang sudah running.

---

## Step 1: Buat Database Baru

```bash
docker exec -it shared-db psql -U postgres

# Jalankan SQL:
CREATE DATABASE newweb_db;
CREATE USER newweb_user WITH ENCRYPTED PASSWORD 'newweb_secret';
GRANT ALL PRIVILEGES ON DATABASE newweb_db TO newweb_user;
\q
```

---

## Step 2: Clone Project Baru

```bash
cd /root
git clone https://github.com/xxx/newweb.git
cd newweb
```

---

## Step 3: Buat docker-compose.yml

Buat file dengan isi (ganti `newweb` sesuai nama project):

```yaml
version: '2.4'

services:
  app:
    build: .
    image: newweb-app
    container_name: newweb-app
    volumes:
      - .:/var/www
      - newweb_vendor:/var/www/vendor
      - newweb_storage:/var/www/storage
    networks:
      - smartagri-network
    environment:
      - DB_HOST=shared-db
      - DB_DATABASE=newweb_db
      - DB_USERNAME=newweb_user
      - DB_PASSWORD=newweb_secret

  nginx:
    image: nginx:alpine
    container_name: newweb-nginx
    volumes:
      - .:/var/www
      - ./docker/nginx.conf:/etc/nginx/conf.d/default.conf
    networks:
      - smartagri-network
    depends_on:
      - app

networks:
  smartagri-network:
    external: true

volumes:
  newweb_vendor:
  newweb_storage:
```

---

## Step 4: Build & Start

```bash
docker-compose build
docker-compose up -d

# Setup Laravel
docker exec newweb-app cp .env.example .env
docker exec newweb-app php artisan key:generate
docker exec newweb-app php artisan migrate --force
docker exec newweb-app chmod -R 775 /var/www/storage
```

---

## Step 5: Tambah Domain ke Reverse Proxy

```bash
nano /root/server-infra/reverse-proxy/nginx.conf
```

Tambah server block:
```nginx
server {
    listen 80;
    server_name newdomain.com www.newdomain.com;

    location / {
        proxy_pass http://newweb-nginx:80;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

---

## Step 6: Reload Reverse Proxy

```bash
docker exec reverse-proxy nginx -s reload
```

---

## Step 7: Verifikasi

```bash
docker ps
curl -I http://newdomain.com
```

---
---

# Troubleshooting

## 502 Bad Gateway
- Container nginx web belum running
- Nama container di `proxy_pass` salah

**Fix:**
```bash
docker ps  # Cek nama container
```

## Database Connection Refused
- Pakai `DB_HOST=shared-db` (bukan localhost)

## Permission Denied Storage
```bash
docker exec [app_container] chmod -R 775 /var/www/storage
docker exec [app_container] chown -R www-data:www-data /var/www/storage
```

## Container Restart Terus
```bash
docker logs [container_name] --tail 50
```

---
---

# Struktur Folder di Server

```
/root/
├── server-infra/           # Infrastructure
│   ├── reverse-proxy/
│   │   ├── docker-compose.yml
│   │   └── nginx.conf      ← Tambah domain di sini
│   └── shared-db/
│       └── docker-compose.yml
│
├── iot/                    # Web 1 (SmartAgri)
│   └── docker-compose.yml
│
└── newweb/                 # Web 2 (baru)
    └── docker-compose.yml
```

---

# Checklist Tambah Web Baru

| Step | Command |
|------|---------|
| 1. Buat database | `docker exec -it shared-db psql -U postgres` |
| 2. Clone project | `git clone ... && cd newweb` |
| 3. Edit docker-compose.yml | Ganti nama container & DB |
| 4. Build & start | `docker-compose up -d` |
| 5. Setup Laravel | `docker exec newweb-app php artisan ...` |
| 6. Edit nginx.conf | Tambah server block |
| 7. Reload proxy | `docker exec reverse-proxy nginx -s reload` |
