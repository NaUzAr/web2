---
description: Workflow sistem Login, User akses, dan Admin akses aplikasi web SmartAgri IoT
---

# 🔐 Workflow Autentikasi & Akses Kontrol SmartAgri IoT

Dokumentasi lengkap sistem login, akses user, dan akses admin pada aplikasi web Laravel SmartAgri IoT.

---

## Arsitektur Autentikasi

### Routes (`routes/web.php`)
```
📌 Public (tanpa login):
  GET  /                → Beranda
  GET  /login           → Form Login
  POST /login           → Proses Login
  GET  /register        → Form Registrasi
  POST /register        → Proses Registrasi
  POST /logout          → Logout

🔒 Protected (membutuhkan login):
  /admin/*              → Admin Routes (khusus role admin)
  /monitoring/*         → User Monitoring Routes
  /device/*/automation  → Automation Config
  /device/*/schedule    → Schedule Management
  /documentation/*      → Dokumentasi
```

---

## 🔑 Sistem Login

### Controller: `app/Http/Controllers/AuthController.php`

### 1. Menampilkan Form Login
```
Route: GET /login
Method: showLoginForm()
View: resources/views/auth/login.blade.php
```

### 2. Proses Login
```
Route: POST /login
Method: login(Request $request)
```

**Alur:**
1. Validasi input `username` dan `password`
2. Cek credentials dengan `Auth::attempt()`
3. Regenerate session untuk keamanan
4. Deteksi mode PWA → redirect ke `/monitoring`
5. Mode web biasa → redirect ke halaman yang dituju (`/`)
6. Jika gagal → tampilkan error

**Contoh Validasi:**
```php
$credentials = $request->validate([
    'username' => ['required'],
    'password' => ['required'],
]);
```

### 3. Proses Registrasi
```
Route: POST /register
Method: register(Request $request)
```

**Validasi:**
- `name`: required, max 255 karakter
- `username`: required, max 50, unique
- `email`: required, valid email, unique
- `password`: required, min 6, confirmed

**Alur:**
1. Validasi input
2. Buat user baru dengan password terenkripsi (`Hash::make()`)
3. Login otomatis setelah registrasi
4. Redirect ke home

### 4. Logout
```
Route: POST /logout
Method: logout(Request $request)
```

**Alur:**
1. `Auth::logout()` - hapus session login
2. `$request->session()->invalidate()` - invalidate session
3. `$request->session()->regenerateToken()` - regenerate CSRF token
4. Redirect ke home

---

## 👤 User Access (Role: User)

### Routes: `/monitoring/*`
**Controller:** `app/Http/Controllers/MonitoringController.php`

User biasa memiliki akses ke fitur monitoring device mereka:

| Route | Method | Fungsi |
|-------|--------|--------|
| `GET /monitoring` | index() | List semua device user |
| `GET /monitoring/add` | create() | Form tambah device via token |
| `POST /monitoring/add` | store() | Proses tambah device |
| `GET /monitoring/device/{id}` | show() | Detail monitoring device |
| `DELETE /monitoring/device/{id}` | destroy() | Hapus device dari monitoring |
| `POST /monitoring/device/{id}/export` | exportCsv() | Export data sensor ke CSV |
| `POST /monitoring/device/{id}/output/{outputId}/toggle` | toggleOutput() | Toggle output device |

### Views User:
```
resources/views/monitoring/
├── index.blade.php      → List device user
├── add_device.blade.php → Form tambah device via token
└── show.blade.php       → Detail monitoring (sensor, output, grafik)
```

### Fitur User:
1. **Tambah Device via Token** - Masukkan token device untuk akses monitoring
2. **Real-time Monitoring** - Lihat data sensor secara real-time via MQTT
3. **Kontrol Output** - Toggle ON/OFF output device (pompa, relay, dll)
4. **Export Data** - Download data sensor dalam format CSV
5. **Automation** - Konfigurasi automation berdasarkan sensor
6. **Schedule** - Set jadwal ON/OFF output

### Automation Routes: `/device/{deviceId}/automation/*`
**Controller:** `app/Http/Controllers/AutomationConfigController.php`

| Route | Method | Fungsi |
|-------|--------|--------|
| `GET /device/{id}/automation` | index() | List automation config |
| `GET /device/{id}/automation/create` | create() | Form buat automation |
| `POST /device/{id}/automation` | store() | Simpan automation |
| `GET /automation/{id}/edit` | edit() | Form edit automation |
| `PUT /automation/{id}` | update() | Update automation |
| `DELETE /automation/{id}` | destroy() | Hapus automation |
| `POST /automation/{id}/toggle` | toggle() | Toggle aktif/nonaktif |

### Schedule Routes: `/device/{userDeviceId}/output/{outputId}/schedule/*`
**Controller:** `app/Http/Controllers/ScheduleController.php`

| Route | Method | Fungsi |
|-------|--------|--------|
| `GET .../schedule` | index() | Halaman manage schedule |
| `POST .../schedule/time` | storeTimeSchedules() | Simpan jadwal waktu |
| `POST .../schedule/sensor` | storeSensorRule() | Simpan rule sensor |

---

## 👑 Admin Access (Role: Admin)

### Routes: `/admin/*`
**Controller:** `app/Http/Controllers/AdminDeviceController.php`

### Proteksi Admin
```php
// Helper: Pastikan yang akses adalah Admin
private function checkAdmin()
{
    if (Auth::user()->role !== 'admin') {
        abort(403, 'Akses Ditolak. Halaman ini khusus Admin.');
    }
}
```

**Catatan:** Untuk menggunakan admin access, user harus memiliki kolom `role` dengan value `'admin'` di tabel `users`.

### Routes Admin:

| Route | Method | Fungsi |
|-------|--------|--------|
| `GET /admin/devices` | index() | List semua device |
| `GET /admin/create-device` | create() | Form buat device baru |
| `POST /admin/create-device` | store() | Simpan device baru |
| `GET /admin/device/{id}/edit` | edit() | Form edit device |
| `PUT /admin/device/{id}` | update() | Update device |
| `DELETE /admin/device/{id}` | destroy() | Hapus device |
| `GET /admin/device/{id}/monitoring` | showMonitoring() | Monitoring admin view |
| `POST /admin/device/{id}/output/{outputId}/toggle` | toggleOutput() | Toggle output |

### Views Admin:
```
resources/views/admin/
├── index.blade.php         → List semua device
├── create_device.blade.php → Form buat device + sensor + output
├── edit.blade.php          → Form edit device
└── mqtt_tester.blade.php   → MQTT Testing Tool
```

### MQTT Tester (Admin Only)

| Route | Method | Fungsi |
|-------|--------|--------|
| `GET /admin/mqtt-tester` | index() | Halaman MQTT Tester |
| `GET /admin/mqtt-tester/device/{id}` | getDeviceDetails() | Get device details |
| `POST /admin/mqtt-tester/send-sensor` | sendSensorData() | Send sensor data test |
| `POST /admin/mqtt-tester/send-output` | sendOutputControl() | Send output control test |
| `POST /admin/mqtt-tester/send-schedule` | sendSchedule() | Send schedule test |
| `POST /admin/mqtt-tester/request-status` | requestStatus() | Request device status |
| `POST /admin/mqtt-tester/send-custom` | sendCustom() | Send custom MQTT message |

---

## 📊 Model Database

### User Model (`app/Models/User.php`)
```php
protected $fillable = [
    'name',
    'username', 
    'email',
    'password',
];

// Relationship
public function userDevices()
{
    return $this->hasMany(UserDevice::class);
}
```

### Tabel `users` harus memiliki kolom:
- `id` - Primary Key
- `name` - Nama lengkap
- `username` - Username unik
- `email` - Email unik
- `password` - Password terenkripsi
- `role` - Role user (`user` atau `admin`)

---

## 🔧 Cara Menjalankan

// turbo
### 1. Jalankan Server Development
```bash
cd d:\01. Program\00. OTW JADI WEB DEV\Beljar\laravel\belajar
php artisan serve
```

// turbo
### 2. Akses Aplikasi
```
Browser: http://127.0.0.1:8000
```

### 3. Test Login
1. Buka `/login`
2. Masukkan username dan password
3. Jika berhasil → redirect ke Beranda
4. Jika PWA mode → redirect ke `/monitoring`

### 4. Test Admin Access
1. Login dengan user yang memiliki `role = 'admin'`
2. Akses `/admin/devices`
3. Jika bukan admin → Error 403

---

## 🛠️ Menambah Admin User

Untuk membuat user menjadi admin, jalankan query di database:

```sql
UPDATE users SET role = 'admin' WHERE username = 'nama_admin';
```

Atau via Tinker:
// turbo
```bash
php artisan tinker
```

Lalu jalankan:
```php
$user = \App\Models\User::where('username', 'nama_admin')->first();
$user->role = 'admin';
$user->save();
```

---

## 📋 Checklist Fitur

### Login & Registrasi
- [x] Form Login dengan validasi
- [x] Form Registrasi dengan konfirmasi password
- [x] Logout dengan invalidate session
- [x] Auto-login setelah registrasi
- [x] Deteksi PWA mode

### User Access
- [x] List device monitoring
- [x] Tambah device via token
- [x] Real-time sensor monitoring
- [x] Toggle output control
- [x] Export data CSV
- [x] Automation configuration
- [x] Schedule management

### Admin Access
- [x] Proteksi role admin
- [x] CRUD device lengkap
- [x] Konfigurasi sensor & output
- [x] MQTT Tester tool
- [x] Admin monitoring view
