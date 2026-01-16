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

# Tunggu database ready
sleep 5
```

---

## Step 5: Buat Database & User (PostgreSQL 15+)

```bash
docker exec -it shared-db psql -U postgres

# Jalankan SQL berikut:
CREATE DATABASE smartagri_db;
CREATE USER smartagri_user WITH ENCRYPTED PASSWORD 'smartagri_secret';
GRANT ALL PRIVILEGES ON DATABASE smartagri_db TO smartagri_user;
\c smartagri_db
GRANT ALL ON SCHEMA public TO smartagri_user;
\q
```

> **PENTING:** PostgreSQL 15+ membutuhkan `GRANT ALL ON SCHEMA public` agar user bisa create table.

---

## Step 6: Clone & Start Web App

```bash
cd /root
git clone https://github.com/NaUzAr/iot.git
cd iot

# Build & Start
docker-compose build
docker-compose up -d
```

---

## Step 7: Setup Laravel

```bash
# Copy .env
docker exec smartagri-app cp .env.example .env

# Generate key
docker exec smartagri-app php artisan key:generate

# Migrate database
docker exec smartagri-app php artisan migrate --force

# Fix permissions
docker exec smartagri-app chmod -R 775 /var/www/storage
docker exec smartagri-app chown -R www-data:www-data /var/www/storage
```

---

## Step 8: Edit Reverse Proxy Config

```bash
nano /root/server-infra/reverse-proxy/nginx.conf
```

Tambah server block:
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

## Step 9: Start Reverse Proxy

```bash
cd /root/server-infra/reverse-proxy
docker-compose up -d
```

---

## Step 10: Verifikasi

```bash
docker ps
curl -I http://smartagri.web.id
```

Seharusnya dapat `HTTP/1.1 200 OK`.

---

## Quick Reference - Urutan Start:

```bash
# 1. Network
docker network create smartagri-network

# 2. Database
cd /root/server-infra/shared-db && docker-compose up -d

# 3. Buat DB & User
docker exec -it shared-db psql -U postgres -c "CREATE DATABASE smartagri_db;"
docker exec -it shared-db psql -U postgres -c "CREATE USER smartagri_user WITH ENCRYPTED PASSWORD 'smartagri_secret';"
docker exec -it shared-db psql -U postgres -c "GRANT ALL PRIVILEGES ON DATABASE smartagri_db TO smartagri_user;"
docker exec -it shared-db psql -U postgres -d smartagri_db -c "GRANT ALL ON SCHEMA public TO smartagri_user;"

# 4. Web App
cd /root/iot && docker-compose build && docker-compose up -d

# 5. Setup Laravel
docker exec smartagri-app cp .env.example .env
docker exec smartagri-app php artisan key:generate
docker exec smartagri-app php artisan migrate --force
docker exec smartagri-app chmod -R 775 /var/www/storage

# 6. Reverse Proxy
cd /root/server-infra/reverse-proxy && docker-compose up -d
```

---
---

# BAGIAN 2: Tambah Domain/Web Baru

---

## Step 1: Buat Database Baru

```bash
docker exec -it shared-db psql -U postgres

# Jalankan SQL:
CREATE DATABASE newweb_db;
CREATE USER newweb_user WITH ENCRYPTED PASSWORD 'newweb_secret';
GRANT ALL PRIVILEGES ON DATABASE newweb_db TO newweb_user;
\c newweb_db
GRANT ALL ON SCHEMA public TO newweb_user;
\q
```

---

## Step 2: Clone Project

```bash
cd /root
git clone https://github.com/xxx/newweb.git
cd newweb
```

---

## Step 3: docker-compose.yml

Pastikan file docker-compose.yml seperti ini:

```yaml
version: '2.4'

services:
  app:
    build: .
    image: newweb-app
    container_name: newweb-app
    restart: unless-stopped
    volumes:
      - .:/var/www
      - newweb_vendor:/var/www/vendor
      - newweb_storage:/var/www/storage
    networks:
      - smartagri-network
    environment:
      - DB_CONNECTION=pgsql
      - DB_HOST=shared-db
      - DB_PORT=5432
      - DB_DATABASE=newweb_db
      - DB_USERNAME=newweb_user
      - DB_PASSWORD=newweb_secret

  nginx:
    image: nginx:alpine
    container_name: newweb-nginx
    restart: unless-stopped
    # TIDAK ADA ports - dihandle reverse proxy
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

## Step 4: docker/nginx.conf

Pastikan file `docker/nginx.conf` seperti ini (HTTP only, PHP-FPM port sesuai):

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name _;
    
    root /var/www/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass newweb-app:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

> **Note:** Ganti `newweb-app` dengan nama container app.
> Gunakan port `9000` (default PHP-FPM) atau `9999` jika custom.

---

## Step 5: Build & Start

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

## Step 6: Tambah Domain ke Reverse Proxy

```bash
nano /root/server-infra/reverse-proxy/nginx.conf
```

Tambah:
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

## Step 7: Reload Reverse Proxy

```bash
docker exec reverse-proxy nginx -s reload
```

---

## Step 8: Verifikasi

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

```bash
docker ps  # Cek nama container
docker logs reverse-proxy --tail 20
```

## 500 Internal Server Error
- Database belum di-migrate
- APP_KEY belum di-generate
- Permission storage denied

```bash
docker exec [app_container] php artisan migrate --force
docker exec [app_container] php artisan key:generate
docker exec [app_container] chmod -R 775 /var/www/storage
```

## Database Permission Denied (PostgreSQL 15+)
- User tidak punya akses ke schema public

```bash
docker exec -it shared-db psql -U postgres -d [database_name]
GRANT ALL ON SCHEMA public TO [username];
\q
```

## Connection Refused (Port 9000)
- PHP-FPM tidak listening di port yang benar
- Cek fastcgi_pass di nginx.conf

```bash
docker exec [app_container] cat /usr/local/etc/php-fpm.d/www.conf | grep listen
# Sesuaikan port di docker/nginx.conf
```

## Container Restart Terus
```bash
docker logs [container_name] --tail 50
```

---
---

# Struktur Folder

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
│   ├── docker-compose.yml
│   └── docker/
│       └── nginx.conf
│
└── newweb/                 # Web 2
    ├── docker-compose.yml
    └── docker/
        └── nginx.conf
```

---

# Checklist Tambah Web Baru

| Step | Command |
|------|---------|
| 1. Buat database | `docker exec -it shared-db psql -U postgres` |
| 2. **GRANT schema** | `\c dbname` lalu `GRANT ALL ON SCHEMA public TO user;` |
| 3. Clone project | `git clone ... && cd newweb` |
| 4. Edit docker-compose.yml | Ganti nama container & DB |
| 5. Edit docker/nginx.conf | Sesuaikan nama container app |
| 6. Build & start | `docker-compose up -d` |
| 7. Setup Laravel | `docker exec app php artisan migrate --force` |
| 8. Edit reverse-proxy nginx.conf | Tambah server block |
| 9. Reload proxy | `docker exec reverse-proxy nginx -s reload` |
