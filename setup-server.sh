#!/bin/bash
# ============================================
# 🐳 Docker Multi-Website Server Setup
# Jalankan di VPS: bash setup-server.sh
#
# Script ini otomatis setup:
# 1. Install Docker & Docker Compose
# 2. Buat Docker network
# 3. Setup PostgreSQL (shared)
# 4. Setup MQTT Broker (Mosquitto)
# 5. Setup Nginx Reverse Proxy
# 6. Setup Firewall (UFW)
# ============================================

set -e

# ╔══════════════════════════════════════╗
# ║        🔧 KONFIGURASI               ║
# ║  Edit bagian ini sesuai kebutuhan    ║
# ╚══════════════════════════════════════╝

BASE_DIR="/opt/docker-apps"
DOCKER_NETWORK="webapps"

# PostgreSQL
PG_USER="webadmin"
PG_PASSWORD="GANTI_PASSWORD_POSTGRES"

# MQTT
MQTT_ALLOW_ANONYMOUS="true"          # Set "false" untuk production
MQTT_USERNAME=""                      # Isi jika MQTT_ALLOW_ANONYMOUS="false"
MQTT_PASSWORD=""                      # Isi jika MQTT_ALLOW_ANONYMOUS="false"

# Nginx Proxy - Domain & Container mapping
# Format: "domain container_name"
# Tambah/hapus sesuai kebutuhan
DOMAINS=(
    "forlizz.online,www.forlizz.online forlizz_app"
    "smartagri.web.id,www.smartagri.web.id smartagri_app"
    "swaratani.id,www.swaratani.id swaratani_app"
)

# ╔══════════════════════════════════════╗
# ║        🚀 MULAI SETUP               ║
# ╚══════════════════════════════════════╝

echo ""
echo "╔══════════════════════════════════════╗"
echo "║  🐳 Docker Multi-Website Setup      ║"
echo "╚══════════════════════════════════════╝"
echo ""

# ─────────────────────────────────────────
# 1. Install Docker
# ─────────────────────────────────────────
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📦 [1/6] Installing Docker..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

if command -v docker &> /dev/null; then
    echo "✅ Docker sudah terinstall: $(docker --version)"
else
    apt update && apt upgrade -y
    curl -fsSL https://get.docker.com -o get-docker.sh && sh get-docker.sh
    rm -f get-docker.sh
    echo "✅ Docker berhasil diinstall"
fi

if ! docker compose version &> /dev/null; then
    apt install docker-compose-plugin -y
    echo "✅ Docker Compose plugin berhasil diinstall"
else
    echo "✅ Docker Compose sudah ada: $(docker compose version)"
fi

# ─────────────────────────────────────────
# 2. Buat struktur folder & network
# ─────────────────────────────────────────
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📁 [2/6] Creating directories & network..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

mkdir -p "$BASE_DIR"/{nginx-proxy,postgres,mqtt/{config,data,log}}

if docker network inspect "$DOCKER_NETWORK" &> /dev/null; then
    echo "✅ Network '$DOCKER_NETWORK' sudah ada"
else
    docker network create "$DOCKER_NETWORK"
    echo "✅ Network '$DOCKER_NETWORK' berhasil dibuat"
fi

# ─────────────────────────────────────────
# 3. Setup PostgreSQL
# ─────────────────────────────────────────
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🐘 [3/6] Setting up PostgreSQL..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

cat > "$BASE_DIR/postgres/docker-compose.yml" <<EOF
services:
  postgres:
    image: postgres:15-alpine
    container_name: shared_postgres
    restart: always
    environment:
      POSTGRES_USER: $PG_USER
      POSTGRES_PASSWORD: $PG_PASSWORD
    volumes:
      - postgres_data:/var/lib/postgresql/data
    ports:
      - "5432:5432"
    networks:
      - $DOCKER_NETWORK

volumes:
  postgres_data:

networks:
  $DOCKER_NETWORK:
    external: true
EOF

cd "$BASE_DIR/postgres" && docker compose up -d
echo "✅ PostgreSQL running (user: $PG_USER)"

# ─────────────────────────────────────────
# 4. Setup MQTT Broker
# ─────────────────────────────────────────
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📡 [4/6] Setting up MQTT Broker..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Buat mosquitto.conf
if [ "$MQTT_ALLOW_ANONYMOUS" = "true" ]; then
cat > "$BASE_DIR/mqtt/config/mosquitto.conf" <<EOF
# Mosquitto Configuration
listener 1883
listener 9001
protocol websockets

allow_anonymous true

persistence true
persistence_location /mosquitto/data/

log_dest file /mosquitto/log/mosquitto.log
log_dest stdout
EOF
else
cat > "$BASE_DIR/mqtt/config/mosquitto.conf" <<EOF
# Mosquitto Configuration
listener 1883
listener 9001
protocol websockets

allow_anonymous false
password_file /mosquitto/config/password.txt

persistence true
persistence_location /mosquitto/data/

log_dest file /mosquitto/log/mosquitto.log
log_dest stdout
EOF
fi

cat > "$BASE_DIR/mqtt/docker-compose.yml" <<EOF
services:
  mqtt:
    image: eclipse-mosquitto:2
    container_name: mqtt_broker
    restart: always
    ports:
      - "1883:1883"
      - "9001:9001"
    volumes:
      - ./config/mosquitto.conf:/mosquitto/config/mosquitto.conf
      - ./data:/mosquitto/data
      - ./log:/mosquitto/log
    networks:
      - $DOCKER_NETWORK

networks:
  $DOCKER_NETWORK:
    external: true
EOF

cd "$BASE_DIR/mqtt" && docker compose up -d

# Setup MQTT password jika bukan anonymous
if [ "$MQTT_ALLOW_ANONYMOUS" = "false" ] && [ -n "$MQTT_USERNAME" ] && [ -n "$MQTT_PASSWORD" ]; then
    sleep 2
    docker exec mqtt_broker mosquitto_passwd -b -c /mosquitto/config/password.txt "$MQTT_USERNAME" "$MQTT_PASSWORD"
    docker compose restart
    echo "✅ MQTT running dengan auth (user: $MQTT_USERNAME)"
else
    echo "✅ MQTT running (anonymous access)"
fi

# ─────────────────────────────────────────
# 5. Setup Nginx Reverse Proxy
# ─────────────────────────────────────────
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🌐 [5/6] Setting up Nginx Reverse Proxy..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Generate nginx.conf dari DOMAINS array
NGINX_SERVERS=""
for entry in "${DOMAINS[@]}"; do
    domains_str=$(echo "$entry" | awk '{print $1}')
    container=$(echo "$entry" | awk '{print $2}')
    # Ganti koma jadi spasi untuk server_name
    server_names=$(echo "$domains_str" | tr ',' ' ')

    NGINX_SERVERS+="
    server {
        listen 80;
        server_name $server_names;

        location / {
            proxy_pass http://$container:80;
            proxy_set_header Host \$host;
            proxy_set_header X-Real-IP \$remote_addr;
            proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto \$scheme;
        }
    }
"
done

cat > "$BASE_DIR/nginx-proxy/nginx.conf" <<NGINXEOF
events {
    worker_connections 1024;
}

http {
    access_log /var/log/nginx/access.log;
    error_log /var/log/nginx/error.log;

    gzip on;
    gzip_types text/plain text/css application/json application/javascript;
$NGINX_SERVERS
    # ===== TEMPLATE: Domain Baru =====
    # server {
    #     listen 80;
    #     server_name DOMAIN.com www.DOMAIN.com;
    #
    #     location / {
    #         proxy_pass http://CONTAINER_NAME:80;
    #         proxy_set_header Host \$host;
    #         proxy_set_header X-Real-IP \$remote_addr;
    #         proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
    #         proxy_set_header X-Forwarded-Proto \$scheme;
    #     }
    # }
}
NGINXEOF

cat > "$BASE_DIR/nginx-proxy/docker-compose.yml" <<EOF
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
      - $DOCKER_NETWORK

networks:
  $DOCKER_NETWORK:
    external: true
EOF

cd "$BASE_DIR/nginx-proxy" && docker compose up -d
echo "✅ Nginx Proxy running (port 80)"

# ─────────────────────────────────────────
# 6. Setup Firewall
# ─────────────────────────────────────────
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🔒 [6/6] Setting up Firewall (UFW)..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

if command -v ufw &> /dev/null; then
    ufw allow 22/tcp    # SSH
    ufw allow 80/tcp    # HTTP
    ufw allow 443/tcp   # HTTPS
    ufw allow 1883/tcp  # MQTT
    ufw allow 9001/tcp  # MQTT WebSocket
    ufw --force enable
    echo "✅ Firewall configured"
else
    echo "⚠️  UFW tidak ditemukan, skip firewall setup"
fi

# ─────────────────────────────────────────
# Done!
# ─────────────────────────────────────────
echo ""
echo "╔══════════════════════════════════════╗"
echo "║  ✅ SERVER SETUP SELESAI!            ║"
echo "╚══════════════════════════════════════╝"
echo ""
echo "📋 Status containers:"
docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
echo ""
echo "📌 Langkah selanjutnya:"
echo "   1. Edit password PostgreSQL di script ini"
echo "   2. Jalankan setup-laravel-project.sh untuk deploy project"
echo "   3. Setup DNS di Cloudflare (A record → VPS IP)"
echo ""
