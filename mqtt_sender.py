import paho.mqtt.client as mqtt
import time
import sys
import random
from datetime import datetime
import json

# MQTT Configuration
MQTT_HOST = "76.13.21.230"
MQTT_PORT = 1883
MQTT_USERNAME = "iot"
MQTT_PASSWORD = "GANTI_DENGAN_PASSWORD_ANDA_ATAU_GUNAKAN_ENV"
MQTT_TOPIC = "/smartgh03/pub"

def get_random_data():
    now = datetime.now()
    # Format: Rabu 21 Jan 26-14:41
    # Note: Python's strftime doesn't support Indonesian day/month names natively easily without locale, 
    # so we'll do a simple mapping or just use English for now if that's acceptable, 
    # but the user example had "Rabu". Let's try to match the format.
    days = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"]
    months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"]
    
    day_str = days[now.weekday()]
    month_str = months[now.month - 1]
    date_str = now.strftime(f"{day_str} %d {month_str} %y-%H:%M")

    # Random sensor values
    ni_ph = round(random.uniform(5.0, 8.0), 2)
    ni_ec = round(random.uniform(1.0, 3.0), 2)
    ni_tds = round(random.uniform(500, 1500), 2)
    ni_lux = round(random.uniform(0, 50000), 2)
    ni_suhu = round(random.uniform(25.0, 35.0), 2)
    ni_kelem = round(random.uniform(40.0, 90.0), 2)

    # Random status (mostly 0, sometimes 1)
    sts_keys = ["sts_air_input", "sts_mixing", "sts_pompa", "sts_fan", "sts_misting", 
                "sts_lampu", "sts_dosing", "sts_ph_up", "sts_air_baku", "sts_air_pupuk", "sts_ph_down"]
    sts_data = {key: 1 if random.random() > 0.9 else 0 for key in sts_keys}

    data_lines = [
        '{"sch8":_:_-0-0-_  -,"sch9":_:_-0-0-_  -,"sch10":_:_-0-0-_  -,"sch11":_:_-0-0-_  -,"sch12":_:_-0-0-_  -,"sch13":_:_-0-0-_  -,"sch14":_:_-0-0-_  -}',
        '{"sch1":14:27-1-2-PUPUK  Min, Sen, Sel, Rab, Kam, Jum, Sab,"sch2":14:30-1-2-BAKU  Min, Sen, Sel, Kam, Jum, Sab,"sch3":_:_-0-0-_  -,"sch4":_:_-0-0-_  -,"sch5":_:_-0-0-_  -,"sch6":_:_-0-0-_  -,"sch7":_:_-0-0-_  -}',
        f'{{"ni_PH":{ni_ph},"ni_EC":{ni_ec},"ni_TDS":{ni_tds},"ni_LUX":{ni_lux},"ni_SUHU":{ni_suhu},"ni_KELEM":{ni_kelem},}}',
        f'{{"waktu":{date_str}}}',
        json.dumps(sts_data), # Use json.dumps for easier dict to string
        '{"bts_ats_suhu":25,"bts_bwh_suhu":80,"bts_ats_kelem":80,"bts_bwh_kelem":60,"bts_ats_ph":7.20,"bts_bwh_ph":4.20,"bts_ats_tds":1800.00,"bts_bwh_tds":850.00}',
        '{"mode_dos":0,"mode_clim":0}'
    ]
    return data_lines

def on_connect(client, userdata, flags, rc):
    if rc == 0:
        print("Connected to MQTT Broker!")
    else:
        print(f"Failed to connect, return code {rc}")
        sys.exit(1)

client = mqtt.Client()
client.username_pw_set(MQTT_USERNAME, MQTT_PASSWORD)
client.on_connect = on_connect

print(f"Connecting to {MQTT_HOST}:{MQTT_PORT}...")
try:
    client.connect(MQTT_HOST, MQTT_PORT, 60)
except Exception as e:
    print(f"Connection failed: {e}")
    sys.exit(1)

client.loop_start()
time.sleep(1) 

print(f"Sending random data to topic {MQTT_TOPIC} (CTRL+C to stop)...")

try:
    while True:
        lines = get_random_data()
        for line in lines:
            client.publish(MQTT_TOPIC, line)
            print(f"Published: {line}") 
            time.sleep(2)
        print(f"Sent batch data at {datetime.now().strftime('%H:%M:%S')}")
       
except KeyboardInterrupt:
    print("\nStopping...")
    client.loop_stop()
    client.disconnect()
    print("Disconnected.")
