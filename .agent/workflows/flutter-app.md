---
description: Membuat aplikasi Flutter SmartAgri IoT dengan fitur sama seperti web
---

# 📱 Flutter SmartAgri IoT App Workflow

Aplikasi Flutter dengan fitur utama:
- 🔐 **Login** - Autentikasi user sama seperti web
- 📱 **List Device** - Melihat daftar device
- 📊 **Monitoring** - Real-time sensor data via MQTT
- ➕ **Tambah Device** - Menambah device baru

## Prasyarat

1. **Flutter SDK** terinstall (versi 3.x)
2. **Android Studio** atau **VS Code** dengan Flutter extension
3. **Emulator Android** atau device fisik untuk testing

// turbo
## Langkah 1: Buat Project Flutter Baru

```bash
flutter create smartagri_app --org id.web.smartagri
cd smartagri_app
```

## Langkah 2: Install Dependencies

Edit `pubspec.yaml` dan tambahkan dependencies:

```yaml
dependencies:
  flutter:
    sdk: flutter
  
  # HTTP & API
  http: ^1.2.0
  
  # MQTT
  mqtt_client: ^10.0.0
  
  # State Management
  provider: ^6.1.1
  
  # Storage
  shared_preferences: ^2.2.2
  flutter_secure_storage: ^9.0.0
  
  # UI Components
  fl_chart: ^0.66.0
  google_fonts: ^6.1.0
  
  # Utilities
  intl: ^0.19.0
  
dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_lints: ^3.0.0
```

// turbo
Lalu jalankan:
```bash
flutter pub get
```

## Langkah 3: Struktur Folder

```
lib/
├── main.dart
├── config/
│   ├── api_config.dart        # API endpoints & base URL
│   ├── mqtt_config.dart       # MQTT broker settings
│   └── theme.dart             # App theme (glassmorphism style)
├── models/
│   ├── user.dart              # User model
│   ├── device.dart            # Device model
│   └── sensor_data.dart       # Sensor data model
├── services/
│   ├── api_service.dart       # REST API calls ke Laravel
│   ├── auth_service.dart      # Login/Logout
│   └── mqtt_service.dart      # MQTT connection & subscribe
├── providers/
│   ├── auth_provider.dart     # Auth state
│   └── device_provider.dart   # Device & sensor state
├── screens/
│   ├── splash_screen.dart     # Loading & auto-login check
│   ├── login_screen.dart      # Login form
│   ├── home_screen.dart       # Dashboard dengan list device
│   ├── add_device_screen.dart # Form tambah device
│   └── monitoring_screen.dart # Real-time sensor monitoring
└── widgets/
    ├── sensor_card.dart       # Card untuk sensor value
    ├── device_card.dart       # Card untuk device item
    └── chart_widget.dart      # Chart sensor data
```

## Langkah 4: Konfigurasi API & MQTT

### `lib/config/api_config.dart`
```dart
class ApiConfig {
  static const String baseUrl = 'https://smartagri.web.id/api';
  
  // Endpoints
  static const String login = '/login';
  static const String register = '/register';
  static const String devices = '/devices';
  static const String sensorData = '/sensor-data';
  static const String schedules = '/schedules';
}
```

### `lib/config/mqtt_config.dart`
```dart
class MqttConfig {
  static const String broker = 'smartagri.web.id';
  static const int tcpPort = 1883;
  static const int wsPort = 9001;
  static const String clientId = 'flutter_smartagri_';
  
  // Topics
  static String sensorTopic(String deviceToken) => 'smartagri/$deviceToken/sensors';
  static String statusTopic(String deviceToken) => 'smartagri/$deviceToken/status';
  static String controlTopic(String deviceToken) => 'smartagri/$deviceToken/control';
}
```

## Langkah 5: MQTT Service

### `lib/services/mqtt_service.dart`
```dart
import 'dart:async';
import 'dart:convert';
import 'package:mqtt_client/mqtt_client.dart';
import 'package:mqtt_client/mqtt_server_client.dart';
import '../config/mqtt_config.dart';

class MqttService {
  MqttServerClient? _client;
  final StreamController<Map<String, dynamic>> _dataController = 
      StreamController<Map<String, dynamic>>.broadcast();
  
  Stream<Map<String, dynamic>> get dataStream => _dataController.stream;
  
  Future<bool> connect() async {
    final clientId = '${MqttConfig.clientId}${DateTime.now().millisecondsSinceEpoch}';
    _client = MqttServerClient(MqttConfig.broker, clientId);
    _client!.port = MqttConfig.tcpPort;
    _client!.keepAlivePeriod = 60;
    _client!.autoReconnect = true;
    
    try {
      await _client!.connect();
      if (_client!.connectionStatus!.state == MqttConnectionState.connected) {
        print('MQTT Connected');
        return true;
      }
    } catch (e) {
      print('MQTT Connection failed: $e');
      _client!.disconnect();
    }
    return false;
  }
  
  void subscribe(String topic) {
    _client?.subscribe(topic, MqttQos.atMostOnce);
    _client?.updates?.listen((List<MqttReceivedMessage<MqttMessage>> messages) {
      for (var message in messages) {
        final payload = message.payload as MqttPublishMessage;
        final data = MqttPublishPayload.bytesToStringAsString(payload.payload.message);
        try {
          final jsonData = jsonDecode(data);
          _dataController.add(jsonData);
        } catch (e) {
          print('Error parsing MQTT data: $e');
        }
      }
    });
  }
  
  void publish(String topic, String message) {
    final builder = MqttClientPayloadBuilder();
    builder.addString(message);
    _client?.publishMessage(topic, MqttQos.atMostOnce, builder.payload!);
  }
  
  void disconnect() {
    _client?.disconnect();
  }
  
  void dispose() {
    _dataController.close();
  }
}
```

## Langkah 6: Fitur Utama yang Harus Dibuat

### 1. Login (sama seperti web)
- [ ] Login screen dengan email/password
- [ ] Token storage dengan flutter_secure_storage
- [ ] Auto-login jika token valid
- [ ] Logout

### 2. List Device
- [ ] List semua devices user di home screen
- [ ] Card design untuk setiap device
- [ ] Tampilkan status online/offline
- [ ] Pull-to-refresh

### 3. Monitoring (Real-time via MQTT)
- [ ] Subscribe ke MQTT topic device
- [ ] Display sensor values real-time
- [ ] Charts untuk historical data
- [ ] Auto-update saat data baru masuk

### 4. Tambah Device
- [ ] Form tambah device baru
- [ ] Input nama device
- [ ] Generate token otomatis
- [ ] Simpan ke database via API

## Langkah 7: Build APK

// turbo
```bash
# Build APK debug
flutter build apk --debug

# Build APK release
flutter build apk --release
```

Output APK berada di: `build/app/outputs/flutter-apk/app-release.apk`

// turbo
## Langkah 8: Testing

```bash
# Run di device/emulator
flutter run
```

## API Endpoints yang Digunakan

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/login` | Login user |
| GET | `/api/devices` | List user devices |
| POST | `/api/devices` | Tambah device baru |
| GET | `/api/devices/{id}` | Device detail |

## MQTT Topics

| Topic Pattern | Direction | Description |
|---------------|-----------|-------------|
| `smartagri/{token}/sensors` | Device → App | Real-time sensor data |
| `smartagri/{token}/status` | Device → App | Device online status |

## Tips

1. **Gunakan Provider** untuk state management
2. **Implement reconnect logic** untuk MQTT
3. **Gunakan StreamBuilder** untuk real-time updates
4. **Test di device fisik** untuk MQTT yang reliable

