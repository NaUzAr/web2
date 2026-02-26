#!/bin/bash

# Setup MQTT Broker
# Based on DOCKER_DEPLOYMENT.md

BASE_DIR="/opt/docker-apps/mqtt"

echo "📂 Creating directories..."
mkdir -p $BASE_DIR/{config,data,log}

echo "📝 Creating docker-compose.yml..."
cat <<EOF > $BASE_DIR/docker-compose.yml
services:
  mqtt:
    image: eclipse-mosquitto:2
    container_name: mqtt_broker
    restart: always
    ports:
      - "1883:1883"   # MQTT
      - "9001:9001"   # WebSocket
    volumes:
      - ./config/mosquitto.conf:/mosquitto/config/mosquitto.conf
      - ./data:/mosquitto/data
      - ./log:/mosquitto/log
    networks:
      - webapps

networks:
  webapps:
    external: true
EOF

echo "📝 Creating mosquitto.conf..."
cat <<EOF > $BASE_DIR/config/mosquitto.conf
# Mosquitto Configuration
listener 1883
listener 9001
protocol websockets

# Authentication
allow_anonymous true

# Persistence
persistence true
persistence_location /mosquitto/data/

# Logging
log_dest file /mosquitto/log/mosquitto.log
log_dest stdout
EOF

echo "🚀 Starting MQTT Broker..."
cd $BASE_DIR
docker compose up -d

echo "✅ MQTT Broker setup complete!"
echo "Test with: docker exec mqtt_broker mosquitto_pub -t 'test' -m 'hello'"
