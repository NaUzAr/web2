"""
SmartAgri IoT - Bulk MQTT Sender (Test Pagination)
===================================================
Mengirim 50 data dummy via MQTT untuk test pagination.

Cara pakai:
1. Jalankan MQTT listener: php artisan mqtt:listen --host=broker.hivemq.com
2. Jalankan script ini: python bulk_sender_mqtt.py
"""

import paho.mqtt.client as mqtt
import json
import random
import time
from datetime import datetime

# ============================================
# KONFIGURASI
# ============================================

MQTT_BROKER = "smartagri.web.id"
MQTT_PORT = 1883

# Topic MQTT - HARUS SAMA dengan mqtt_topic device di database!
MQTT_TOPIC = "awsnya/bopal/1"

# Token device
TOKEN = "hNdkptRyxrZZHKv0"

# Jumlah data
TOTAL_DATA = 50

# Delay antar pengiriman (detik)
DELAY = 0.3

# Sensor ranges (AWS)
SENSOR_RANGES = {
    "temperature": (20.0, 35.0),
    "humidity": (40.0, 90.0),
    "rainfall": (0.0, 50.0),
    "wind_speed": (0.0, 20.0),
    "wind_direction": (0.0, 360.0),
}

# ============================================
# FUNGSI
# ============================================

def generate_sensor_data():
    data = {"token": TOKEN}
    for sensor, (min_val, max_val) in SENSOR_RANGES.items():
        data[sensor] = round(random.uniform(min_val, max_val), 2)
    return data

def main():
    print("=" * 60)
    print("🌱 SmartAgri IoT - Bulk MQTT Sender")
    print("=" * 60)
    print(f"📡 Broker: {MQTT_BROKER}:{MQTT_PORT}")
    print(f"📮 Topic: {MQTT_TOPIC}")
    print(f"🔑 Token: {TOKEN}")
    print(f"📊 Total data: {TOTAL_DATA}")
    print("-" * 60)
    
    # Connect MQTT
    client = mqtt.Client(mqtt.CallbackAPIVersion.VERSION2)
    
    print("\n🔌 Menghubungkan ke MQTT broker...")
    client.connect(MQTT_BROKER, MQTT_PORT, 60)
    client.loop_start()
    time.sleep(2)
    
    print(f"✅ Terhubung! Mengirim {TOTAL_DATA} data...\n")
    
    success = 0
    for i in range(1, TOTAL_DATA + 1):
        data = generate_sensor_data()
        result = client.publish(MQTT_TOPIC, json.dumps(data), qos=1)
        
        if result.rc == mqtt.MQTT_ERR_SUCCESS:
            success += 1
            print(f"   [{i}/{TOTAL_DATA}] ✅ Temp: {data['temperature']:.1f}°C, Humid: {data['humidity']:.1f}%")
        else:
            print(f"   [{i}/{TOTAL_DATA}] ❌ Gagal publish")
        
        time.sleep(DELAY)
    
    client.loop_stop()
    client.disconnect()
    
    print("\n" + "=" * 60)
    print(f"🎉 Selesai! {success}/{TOTAL_DATA} data terkirim via MQTT")
    print("=" * 60)
    print("\n⚠️  PENTING: Pastikan mqtt_topic device di database = " + MQTT_TOPIC)
    print("   Jika tidak match, update via Admin Panel > Edit Device")

if __name__ == "__main__":
    main()
