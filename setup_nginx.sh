#!/bin/bash

# Setup Nginx Proxy
# Based on DOCKER_DEPLOYMENT.md

BASE_DIR="/opt/docker-apps/nginx-proxy"

echo "📂 Creating directories..."
mkdir -p $BASE_DIR

echo "📝 Creating docker-compose.yml..."
cat <<EOF > $BASE_DIR/docker-compose.yml
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
EOF

echo "📝 Creating nginx.conf..."
cat <<EOF > $BASE_DIR/nginx.conf
events {
    worker_connections 1024;
}

http {
    # Logging
    access_log /var/log/nginx/access.log;
    error_log /var/log/nginx/error.log;

    # Gzip compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript;

    # ===== DOMAIN 1: example.com (Adjust as needed) =====
    # server {
    #     listen 80;
    #     server_name example.com www.example.com;
    #     
    #     location / {
    #         proxy_pass http://example_app:80;
    #         proxy_set_header Host \$host;
    #         proxy_set_header X-Real-IP \$remote_addr;
    #         proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
    #         proxy_set_header X-Forwarded-Proto \$scheme;
    #     }
    # }
}
EOF

echo "🚀 Starting Nginx Proxy..."
cd $BASE_DIR
docker compose up -d

echo "✅ Nginx Proxy setup complete!"
echo "Edit $BASE_DIR/nginx.conf to add your domains, then run: docker compose restart"
