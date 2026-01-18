# 🐳 Panduan Deploy Multi-Website Laravel dengan Docker + Cloudflare

Panduan untuk deploy **multiple Laravel websites** ke **satu VPS** menggunakan **Docker** dan **Cloudflare** untuk SSL.

## 📋 Overview Arsitektur

```
         User ──▶ Cloudflare (HTTPS) ──▶ VPS Nginx (HTTP) ──▶ Laravel Containers
```

---

## 📁 Struktur Folder di VPS

```
/opt/docker-apps/
├── nginx-proxy/           # Reverse proxy
│   ├── docker-compose.yml
│   └── nginx.conf
├── forlizz/               # Project 1
│   ├── docker-compose.yml
│   ├── Dockerfile
│   ├── nginx.conf
│   └── src/
├── smartagri/             # Project 2
│   └── ... (sama seperti forlizz)
└── postgres/              # Shared database
    └── docker-compose.yml
```

---

## ☁️ BAGIAN 1: Setup Cloudflare

1. **Add domain di Cloudflare Dashboard**
2. **Ubah nameserver di domain registrar**
3. **Set DNS Records:**

| Type | Name | Content | Proxy |
|------|------|---------|-------|
| A | @ | 203.194.115.76 | ☁️ Proxied |
| A | www | 203.194.115.76 | ☁️ Proxied |

4. **SSL/TLS → Pilih "Flexible"**

---

## 🔧 BAGIAN 2: Setup VPS

```bash
ssh root@IP_VPS
apt update && apt upgrade -y
curl -fsSL https://get.docker.com -o get-docker.sh && sh get-docker.sh
apt install docker-compose-plugin -y
mkdir -p /opt/docker-apps/{nginx-proxy,forlizz/src,smartagri/src,postgres}
docker network create webapps
```

---

## 🗄️ BAGIAN 3: Setup PostgreSQL

File: `/opt/docker-apps/postgres/docker-compose.yml`

```yaml
services:
  postgres:
    image: postgres:15-alpine
    container_name: shared_postgres
    restart: always
    environment:
      POSTGRES_USER: webadmin
      POSTGRES_PASSWORD: password_kuat_anda
    volumes:
      - postgres_data:/var/lib/postgresql/data
      - ./init-databases.sql:/docker-entrypoint-initdb.d/init.sql
    networks:
      - webapps
    ports:
      - "5432:5432"

volumes:
  postgres_data:

networks:
  webapps:
    external: true
```

File: `/opt/docker-apps/postgres/init-databases.sql`

```sql
CREATE DATABASE db_forlizz;
CREATE DATABASE db_smartagri;
GRANT ALL PRIVILEGES ON DATABASE db_forlizz TO webadmin;
GRANT ALL PRIVILEGES ON DATABASE db_smartagri TO webadmin;
```

```bash
cd /opt/docker-apps/postgres && docker compose up -d
```

---

## 🌐 BAGIAN 4: Nginx Reverse Proxy (HTTP Only - Cloudflare handles SSL)

File: `/opt/docker-apps/nginx-proxy/docker-compose.yml`

```yaml
services:
  nginx-proxy:
    image: nginx:alpine
    container_name: nginx_proxy
    restart: always
    ports:
      - "80:80"
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf:ro
    networks:
      - webapps

networks:
  webapps:
    external: true
```

File: `/opt/docker-apps/nginx-proxy/nginx.conf`

```nginx
events {
    worker_connections 1024;
}
http {
    access_log /var/log/nginx/access.log;
    error_log /var/log/nginx/error.log;
    gzip on;
    gzip_types text/plain text/css application/json application/javascript;

    # forlizz.online
    server {
        listen 80;
        server_name forlizz.online www.forlizz.online;
        location / {
            proxy_pass http://forlizz_app:80;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto $scheme;
        }
    }

    # smartagri.web.id
    server {
        listen 80;
        server_name smartagri.web.id www.smartagri.web.id;
        location / {
            proxy_pass http://smartagri_app:80;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto $scheme;
        }
    }
}
```

---

## 📦 BAGIAN 5: Laravel Container

### Dockerfile

```dockerfile
FROM php:8.4-fpm-alpine

RUN apk add --no-cache nginx supervisor libpng-dev libzip-dev postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql zip gd bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY nginx.conf /etc/nginx/http.d/default.conf
RUN mkdir -p /etc/supervisor.d
COPY supervisord.ini /etc/supervisor.d/supervisord.ini

WORKDIR /var/www/html
COPY src/ /var/www/html/
RUN composer install --optimize-autoloader --no-dev
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
```

### nginx.conf (Internal)

```nginx
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    index index.php;
    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### supervisord.ini

```ini
[supervisord]
nodaemon=true

[program:php-fpm]
command=/usr/local/sbin/php-fpm
autostart=true
autorestart=true

[program:nginx]
command=/usr/sbin/nginx -g "daemon off;"
autostart=true
autorestart=true
```

### docker-compose.yml

```yaml
services:
  app:
    build: .
    container_name: forlizz_app  # atau smartagri_app
    restart: always
    environment:
      APP_ENV: production
      APP_DEBUG: "false"
      APP_URL: https://forlizz.online
      DB_CONNECTION: pgsql
      DB_HOST: shared_postgres
      DB_DATABASE: db_forlizz
      DB_USERNAME: webadmin
      DB_PASSWORD: password_kuat_anda
    networks:
      - webapps

networks:
  webapps:
    external: true
```

---

## 🚀 BAGIAN 6: Deploy

```bash
# Upload source
scp -r "D:\path\to\project\*" root@IP_VPS:/opt/docker-apps/forlizz/src/

# Start services
cd /opt/docker-apps/postgres && docker compose up -d
cd /opt/docker-apps/forlizz && docker compose up -d --build
cd /opt/docker-apps/nginx-proxy && docker compose up -d

# Migrations
docker exec forlizz_app php artisan key:generate
docker exec forlizz_app php artisan migrate --force
docker exec forlizz_app php artisan storage:link
```

---

## ➕ BAGIAN 7: Tambah Website Baru

```bash
# 1. Tambah DNS di Cloudflare (A record → IP VPS, Proxied)
# 2. Buat database
docker exec -it shared_postgres psql -U webadmin -c "CREATE DATABASE db_newsite;"

# 3. Copy template & edit docker-compose.yml
mkdir -p /opt/docker-apps/newsite/src
cp /opt/docker-apps/forlizz/{Dockerfile,nginx.conf,supervisord.ini} /opt/docker-apps/newsite/

# 4. Tambah server block di nginx-proxy/nginx.conf
# 5. Build & deploy
docker compose up -d --build
```

---

## 🆘 Troubleshooting

### 403 Forbidden
```bash
docker exec app_name chmod -R 755 /var/www/html/public
docker exec app_name chown -R www-data:www-data /var/www/html/public
```

### .env / APP_KEY Missing
```bash
docker exec app_name sh -c 'echo "APP_NAME=Laravel
APP_ENV=production
APP_KEY=
DB_CONNECTION=pgsql
DB_HOST=shared_postgres
DB_DATABASE=db_name
DB_USERNAME=webadmin
DB_PASSWORD=password" > .env'
docker exec app_name php artisan key:generate
```

---

## 🛠️ Commands Berguna

```bash
docker ps                    # Lihat containers
docker logs app_name         # Lihat logs
docker exec -it app_name sh  # Masuk container
docker compose up -d --build # Rebuild
```
