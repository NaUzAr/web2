import paho.mqtt.client as mqtt
import json
import time

# Connect to broker - SmartAgri Server
client = mqtt.Client()
client.connect("smartagri.web.id", 1883)

# Topic - sesuaikan dengan mqtt_topic device yang ada di database
topic = "smartagri/test"

# Format sesuai ESP32: <dat|{JSON}|>
sensor_data = {
    "ni_PH": 6.8,
    "ni_EC": 1200,
    "ni_TDS": 850,
    "ni_LUX": 1500,
    "ni_SUHU": 28.5,
    "ni_KELEM": 65
}

payload = "<dat|" + json.dumps(sensor_data) + "|>"

print(f"Sending to topic: {topic}")
print(f"Payload: {payload}")

client.publish(topic, payload)
print("✅ Data sent!")

client.disconnect()
