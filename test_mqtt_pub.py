import paho.mqtt.client as mqtt
import time
import json

# MQTT Config
MQTT_HOST = "203.194.115.76"
MQTT_PORT = 1883
MQTT_USER = "iot"
MQTT_PASS = "smartgh"

# Topic (sesuai mqtt_topic device + /sub)
TOPIC = "/gh01/sub"

# Test data format ESP32 (Counter 1 - Sensor Data)
sensor_data = {
    "ni_PH": 7.2,
    "ni_EC": 1.8,
    "ni_TDS": 650,
    "ni_LUX": 1200,
    "ni_SUHU": 28.5,
    "ni_KELEM": 65.3
}

# Format message: <dat|{JSON}|>
message = f'<dat|{json.dumps(sensor_data)}|>'

print(f"Publishing to: {TOPIC}")
print(f"Message: {message}")
print()

client = mqtt.Client()
client.username_pw_set(MQTT_USER, MQTT_PASS)
client.connect(MQTT_HOST, MQTT_PORT)

client.publish(TOPIC, message)
time.sleep(1)

print("✅ Sensor data published successfully!")
client.disconnect()
