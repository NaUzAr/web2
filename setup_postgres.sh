#!/bin/bash

# Setup PostgreSQL
# Based on DOCKER_DEPLOYMENT.md

BASE_DIR="/opt/docker-apps/postgres"

echo "📂 Creating directories..."
mkdir -p $BASE_DIR

echo "📝 Creating docker-compose.yml..."
# Note: You might want to change POSTGRES_PASSWORD before running this, or edit the file afterwards.
cat <<EOF > $BASE_DIR/docker-compose.yml
services:
  postgres:
    image: postgres:15-alpine
    container_name: shared_postgres
    restart: always
    environment:
      POSTGRES_USER: webadmin
      POSTGRES_PASSWORD: YOUR_PASSWORD
    volumes:
      - postgres_data:/var/lib/postgresql/data
    networks:
      - webapps

volumes:
  postgres_data:

networks:
  webapps:
    external: true
EOF

echo "🚀 Starting PostgreSQL..."
cd $BASE_DIR
docker compose up -d

echo "✅ PostgreSQL setup complete!"
echo "Don't forget to change YOUR_PASSWORD in $BASE_DIR/docker-compose.yml if needed."
