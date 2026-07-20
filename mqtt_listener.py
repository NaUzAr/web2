import paho.mqtt.client as mqtt
import json
from datetime import datetime

# MQTT Config
MQTT_HOST = "203.194.115.76"
MQTT_PORT = 1883
MQTT_USER = "iot"
MQTT_PASS = "GANTI_DENGAN_PASSWORD_ANDA"

# Topics to subscribe
TOPICS = [
    "smartgh01/pub",
    "smartgh01/sub",
]

def on_connect(client, userdata, flags, rc):
    if rc == 0:
        print("✅ Connected to MQTT Broker!")
        print(f"   Host: {MQTT_HOST}:{MQTT_PORT}")
        print()
        
        # Subscribe to all topics
        for topic in TOPICS:
            client.subscribe(topic)
            print(f"📡 Subscribed to: {topic}")
        
        print()
        print("👂 Listening for messages... (Press Ctrl+C to stop)")
        print("─" * 50)
    else:
        print(f"❌ Connection failed with code {rc}")

def on_message(client, userdata, msg):
    timestamp = datetime.now().strftime("%H:%M:%S")
    topic = msg.topic
    payload = msg.payload.decode('utf-8')
    
    print(f"\n[{timestamp}] 📨 Topic: {topic}")
    print(f"           Raw: {payload}")
    
    # Try to parse <dat|{JSON}|> format
    if payload.startswith("<dat|") and payload.endswith("|>"):
        try:
            json_str = payload[5:-2]  # Remove <dat| and |>
            data = json.loads(json_str)
            print(f"           Parsed JSON:")
            for key, value in data.items():
                print(f"           • {key}: {value}")
        except json.JSONDecodeError:
            print("           ⚠️  Failed to parse JSON")
    
    # Log to file
    with open("mqtt_log.txt", "a") as f:
        f.write(f"[{datetime.now()}] {topic} | {payload}\n")

def main():
    print("🚀 Starting Python MQTT Listener...")
    print()
    
    client = mqtt.Client()
    client.username_pw_set(MQTT_USER, MQTT_PASS)
    client.on_connect = on_connect
    client.on_message = on_message
    
    try:
        client.connect(MQTT_HOST, MQTT_PORT, 60)
        client.loop_forever()
    except KeyboardInterrupt:
        print("\n\n👋 Stopping listener...")
        client.disconnect()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    main()
