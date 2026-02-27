#!/bin/bash
# ============================================
# 🐳 Setup Laravel Project di Docker
# Jalankan di VPS: bash setup-laravel-project.sh
#
# Script ini otomatis:
# 1. Buat folder project
# 2. Buat database PostgreSQL
# 3. Generate Dockerfile, docker-compose, nginx.conf, supervisord
# 4. Clone source code dari GitHub
# 5. Build & start container
# 6. Setup Laravel (key, migrate, storage:link, cache)
# 7. Tambah domain ke Nginx Proxy
# ============================================

set -e

# ╔══════════════════════════════════════╗
# ║        🔧 KONFIGURASI               ║
# ║  Edit bagian ini sesuai kebutuhan    ║
# ╚══════════════════════════════════════╝

BASE_DIR="/opt/docker-apps"
DOCKER_NETWORK="webapps"

# ── Project ──
PROJECT_NAME="swaratani_web"            # Nama folder project
CONTAINER_NAME="swaratani_app"          # Nama container Docker
GIT_REPO="https://github.com/USERNAME/REPO.git"
GIT_BRANCH="main"
PHP_VERSION="8.2"                       # PHP version (8.2, 8.3, 8.4)

# ── Domain ──
DOMAIN="swaratani.id"
DOMAIN_WWW="www.swaratani.id"

# ── Database ──
DB_NAME="db_swaratani"
DB_HOST="shared_postgres"               # Nama container PostgreSQL
DB_USER="webadmin"
DB_PASSWORD="GANTI_PASSWORD_POSTGRES"

# ── App ──
APP_NAME="Swaratani"
APP_URL="https://$DOMAIN"
APP_ENV="production"

# ── MQTT ──
MQTT_HOST="203.194.115.76"              # IP VPS atau hostname
MQTT_PORT="1883"
MQTT_USERNAME="iot"
MQTT_PASSWORD="smartgh"
MQTT_TOPIC_PUB="/smartgh01/pub"
MQTT_TOPIC_SUB="/smartgh01/sub"

# ── Email (Resend) ──
MAIL_FROM_ADDRESS="noreply@swaratani.id"
MAIL_FROM_NAME="Swaratani IoT"
RESEND_API_KEY="YOUR_RESEND_API_KEY"

# ╔══════════════════════════════════════╗
# ║        🚀 MULAI SETUP               ║
# ╚══════════════════════════════════════╝

PROJECT_DIR="$BASE_DIR/$PROJECT_NAME"

echo ""
echo "╔══════════════════════════════════════╗"
echo "║  🐳 Laravel Project Setup           ║"
echo "║  Project: $PROJECT_NAME"
echo "║  Domain:  $DOMAIN"
echo "╚══════════════════════════════════════╝"
echo ""

# ─────────────────────────────────────────
# 1. Buat folder project
# ─────────────────────────────────────────
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📁 [1/7] Creating project directory..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

mkdir -p "$PROJECT_DIR/src"
echo "✅ Directory: $PROJECT_DIR"

# ─────────────────────────────────────────
# 2. Buat database PostgreSQL
# ─────────────────────────────────────────
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🐘 [2/7] Creating database..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Cek apakah database sudah ada
if docker exec shared_postgres psql -U "$DB_USER" -lqt | cut -d \| -f 1 | grep -qw "$DB_NAME"; then
    echo "✅ Database '$DB_NAME' sudah ada"
else
    docker exec shared_postgres psql -U "$DB_USER" -c "CREATE DATABASE $DB_NAME;"
    echo "✅ Database '$DB_NAME' berhasil dibuat"
fi

# ─────────────────────────────────────────
# 3. Generate Docker files
# ─────────────────────────────────────────
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📝 [3/7] Generating Docker files..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# --- Dockerfile ---
cat > "$PROJECT_DIR/Dockerfile" <<EOF
FROM php:${PHP_VERSION}-fpm-alpine

RUN apk add --no-cache nginx supervisor libpng-dev libzip-dev postgresql-dev \\
    && docker-php-ext-install pdo pdo_pgsql zip gd bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY nginx.conf /etc/nginx/http.d/default.conf
COPY supervisord.ini /etc/supervisor.d/supervisord.ini

WORKDIR /var/www/html
COPY src/ /var/www/html/

# PENTING: Hapus vendor saja, JANGAN hapus composer.lock
# --no-scripts untuk menghindari error Sanctum
RUN rm -rf vendor && composer install --optimize-autoloader --no-dev --no-interaction --no-scripts

RUN chown -R www-data:www-data /var/www/html \\
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
EOF
echo "  ✅ Dockerfile"

# --- nginx.conf ---
cat > "$PROJECT_DIR/nginx.conf" <<'EOF'
server {
    listen 80;
    root /var/www/html/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
EOF
echo "  ✅ nginx.conf"

# --- supervisord.ini ---
cat > "$PROJECT_DIR/supervisord.ini" <<EOF
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

[program:mqtt-listener]
command=php /var/www/html/artisan mqtt:listen
autostart=true
autorestart=true
stdout_logfile=/var/www/html/storage/logs/mqtt.log
stderr_logfile=/var/www/html/storage/logs/mqtt-error.log
EOF
echo "  ✅ supervisord.ini"

# --- docker-compose.yml ---
cat > "$PROJECT_DIR/docker-compose.yml" <<EOF
services:
  app:
    build: .
    container_name: $CONTAINER_NAME
    restart: always
    environment:
      # App
      APP_NAME: $APP_NAME
      APP_ENV: $APP_ENV
      APP_KEY: base64:WILL_BE_GENERATED
      APP_DEBUG: "false"
      APP_URL: $APP_URL
      # Database
      DB_CONNECTION: pgsql
      DB_HOST: $DB_HOST
      DB_PORT: 5432
      DB_DATABASE: $DB_NAME
      DB_USERNAME: $DB_USER
      DB_PASSWORD: $DB_PASSWORD
      # Session & Cache
      SESSION_DRIVER: file
      CACHE_STORE: file
      QUEUE_CONNECTION: sync
      LOG_CHANNEL: stack
      FILESYSTEM_DISK: local
      # MQTT
      MQTT_HOST: $MQTT_HOST
      MQTT_PORT: $MQTT_PORT
      MQTT_USERNAME: $MQTT_USERNAME
      MQTT_PASSWORD: $MQTT_PASSWORD
      MQTT_TOPIC_PUB: $MQTT_TOPIC_PUB
      MQTT_TOPIC_SUB: $MQTT_TOPIC_SUB
      # Email
      MAIL_MAILER: resend
      MAIL_FROM_ADDRESS: "$MAIL_FROM_ADDRESS"
      MAIL_FROM_NAME: "$MAIL_FROM_NAME"
      RESEND_API_KEY: $RESEND_API_KEY
    volumes:
      - ./src/storage:/var/www/html/storage
    networks:
      - $DOCKER_NETWORK

networks:
  $DOCKER_NETWORK:
    external: true
EOF
echo "  ✅ docker-compose.yml"

# --- deploy.sh ---
cat > "$PROJECT_DIR/deploy.sh" <<EOF
#!/bin/bash
# Deploy script untuk $PROJECT_NAME
set -e

PROJECT_DIR="$PROJECT_DIR"
CONTAINER="$CONTAINER_NAME"

echo "🔄 Pulling latest code from GitHub..."
cd "\$PROJECT_DIR/src"
git pull origin $GIT_BRANCH

echo "🔨 Building & restarting Docker..."
cd "\$PROJECT_DIR"
docker compose up -d --build

echo "⏳ Waiting for container to start..."
sleep 5

echo "🗄️ Running migrations..."
docker exec \$CONTAINER php artisan migrate --force

echo "⚡ Caching config..."
docker exec \$CONTAINER php artisan config:cache
docker exec \$CONTAINER php artisan route:cache
docker exec \$CONTAINER php artisan view:cache

echo "🔄 Restarting container..."
docker restart \$CONTAINER

echo ""
echo "✅ Deploy selesai! Cek: $APP_URL"
echo ""
docker ps | grep \$CONTAINER
EOF
chmod +x "$PROJECT_DIR/deploy.sh" 2>/dev/null || true
echo "  ✅ deploy.sh"

# ─────────────────────────────────────────
# 4. Clone source code
# ─────────────────────────────────────────
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📥 [4/7] Cloning source code..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

if [ -f "$PROJECT_DIR/src/composer.json" ]; then
    echo "✅ Source code sudah ada, skip clone"
else
    cd "$PROJECT_DIR/src"
    git clone "$GIT_REPO" . || {
        echo "❌ Git clone gagal! Pastikan URL repo benar:"
        echo "   GIT_REPO=$GIT_REPO"
        echo ""
        echo "💡 Atau upload manual ke: $PROJECT_DIR/src/"
        exit 1
    }
    echo "✅ Source code cloned"
fi

# ─────────────────────────────────────────
# 5. Build & start container
# ─────────────────────────────────────────
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🔨 [5/7] Building & starting container..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

cd "$PROJECT_DIR"
docker compose up -d --build

echo "⏳ Waiting for container to start..."
sleep 5
echo "✅ Container started"

# ─────────────────────────────────────────
# 6. Setup Laravel
# ─────────────────────────────────────────
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "⚡ [6/7] Setting up Laravel..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Copy .env (wajib agar key:generate jalan)
docker exec "$CONTAINER_NAME" cp .env.example .env
echo "  ✅ .env created"

# Generate Application Key
docker exec "$CONTAINER_NAME" php artisan key:generate
echo "  ✅ APP_KEY generated"

# Fix Permissions
docker exec "$CONTAINER_NAME" chown -R www-data:www-data storage bootstrap/cache
docker exec "$CONTAINER_NAME" chmod -R 775 storage bootstrap/cache
echo "  ✅ Permissions fixed"

# Migrate & Link Storage
docker exec "$CONTAINER_NAME" php artisan migrate --force
echo "  ✅ Database migrated"

docker exec "$CONTAINER_NAME" php artisan storage:link
echo "  ✅ Storage linked"

# Cache Config
docker exec "$CONTAINER_NAME" php artisan config:cache
docker exec "$CONTAINER_NAME" php artisan route:cache
docker exec "$CONTAINER_NAME" php artisan view:cache
echo "  ✅ Config/routes/views cached"

# Restart container
docker restart "$CONTAINER_NAME"
echo "  ✅ Container restarted"

# Cek MQTT Listener
sleep 3
echo ""
echo "📡 MQTT Listener status:"
docker exec "$CONTAINER_NAME" supervisorctl status mqtt-listener || echo "⚠️  MQTT listener belum aktif"

# ─────────────────────────────────────────
# 7. Tambah ke Nginx Proxy
# ─────────────────────────────────────────
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🌐 [7/7] Adding to Nginx Proxy..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

NGINX_CONF="$BASE_DIR/nginx-proxy/nginx.conf"

# Cek apakah domain sudah ada di nginx.conf
if grep -q "$DOMAIN" "$NGINX_CONF" 2>/dev/null; then
    echo "✅ Domain '$DOMAIN' sudah ada di nginx.conf"
else
    # Tambahkan server block sebelum closing brace terakhir
    # Buat temporary file
    SERVER_BLOCK="
    # ===== $PROJECT_NAME =====
    server {
        listen 80;
        server_name $DOMAIN $DOMAIN_WWW;

        location / {
            proxy_pass http://${CONTAINER_NAME}:80;
            proxy_set_header Host \\\$host;
            proxy_set_header X-Real-IP \\\$remote_addr;
            proxy_set_header X-Forwarded-For \\\$proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto \\\$scheme;
        }
    }"

    # Insert sebelum baris terakhir "}" di nginx.conf
    sed -i "/^}/i\\${SERVER_BLOCK}" "$NGINX_CONF"

    echo "✅ Domain '$DOMAIN' ditambahkan ke nginx.conf"
fi

# Restart nginx proxy
docker compose -f "$BASE_DIR/nginx-proxy/docker-compose.yml" restart
echo "✅ Nginx Proxy restarted"

# ─────────────────────────────────────────
# Done!
# ─────────────────────────────────────────
echo ""
echo "╔══════════════════════════════════════╗"
echo "║  ✅ PROJECT SETUP SELESAI!           ║"
echo "╚══════════════════════════════════════╝"
echo ""
echo "📋 Status:"
docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}" | grep -E "(NAMES|$CONTAINER_NAME)"
echo ""
echo "📌 Langkah selanjutnya:"
echo "   1. Setup DNS di Cloudflare: A record → VPS IP"
echo "   2. Cloudflare SSL/TLS → Flexible"
echo "   3. Cek website: $APP_URL"
echo ""
echo "🔄 Untuk update berikutnya, jalankan:"
echo "   bash $PROJECT_DIR/deploy.sh"
echo ""
