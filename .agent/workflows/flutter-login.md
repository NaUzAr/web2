---
description: Workflow pengembangan fitur Login untuk aplikasi Flutter SmartAgri IoT
---

# Flutter Login Development Workflow

## Prerequisites
- Flutter SDK sudah terinstall
- Project Flutter sudah dibuat (gunakan `/flutter-app` jika belum)
- Laravel API sudah berjalan

---

## Step 1: Setup Laravel API Endpoint untuk Login

### 1.1 Buat API Route di Laravel
Tambahkan route di `routes/api.php`:

```php
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [App\Http\Controllers\Api\AuthController::class, 'user'])->middleware('auth:sanctum');
```

### 1.2 Buat API AuthController
Buat file `app/Http/Controllers/Api/AuthController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('flutter-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }
}
```

### 1.3 Install Laravel Sanctum (jika belum)
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 1.4 Update User Model
Tambahkan trait di `app/Models/User.php`:
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    // ...
}
```

---

## Step 2: Setup Flutter Project

### 2.1 Tambah Dependencies di `pubspec.yaml`
```yaml
dependencies:
  http: ^1.1.0
  shared_preferences: ^2.2.2
  provider: ^6.1.1
```

// turbo
### 2.2 Install Dependencies
```bash
flutter pub get
```

---

## Step 3: Buat Service Layer di Flutter

### 3.1 Buat `lib/services/api_service.dart`
```dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  // Ganti dengan URL API Laravel kamu
  static const String baseUrl = 'http://YOUR_LARAVEL_URL/api';
  
  // Login
  static Future<Map<String, dynamic>> login(String username, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'username': username,
        'password': password,
      }),
    );
    
    return jsonDecode(response.body);
  }
  
  // Save Token
  static Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }
  
  // Get Token
  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }
  
  // Logout
  static Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }
  
  // Check if logged in
  static Future<bool> isLoggedIn() async {
    final token = await getToken();
    return token != null && token.isNotEmpty;
  }
}
```

---

## Step 4: Buat Auth Provider

### 4.1 Buat `lib/providers/auth_provider.dart`
```dart
import 'package:flutter/material.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  bool _isLoading = false;
  bool _isLoggedIn = false;
  String? _errorMessage;
  Map<String, dynamic>? _user;

  bool get isLoading => _isLoading;
  bool get isLoggedIn => _isLoggedIn;
  String? get errorMessage => _errorMessage;
  Map<String, dynamic>? get user => _user;

  Future<bool> login(String username, String password) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final response = await ApiService.login(username, password);
      
      if (response['success'] == true) {
        await ApiService.saveToken(response['data']['token']);
        _user = response['data']['user'];
        _isLoggedIn = true;
        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        _errorMessage = response['message'] ?? 'Login gagal';
        _isLoading = false;
        notifyListeners();
        return false;
      }
    } catch (e) {
      _errorMessage = 'Terjadi kesalahan koneksi';
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    await ApiService.logout();
    _isLoggedIn = false;
    _user = null;
    notifyListeners();
  }

  Future<void> checkLoginStatus() async {
    _isLoggedIn = await ApiService.isLoggedIn();
    notifyListeners();
  }
}
```

---

## Step 5: Buat Login Screen

### 5.1 Buat `lib/screens/login_screen.dart`
```dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _usernameController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _obscurePassword = true;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [Color(0xFF2E7D32), Color(0xFF1B5E20)],
          ),
        ),
        child: SafeArea(
          child: Center(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(24),
              child: Card(
                elevation: 8,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(16),
                ),
                child: Padding(
                  padding: const EdgeInsets.all(24),
                  child: Form(
                    key: _formKey,
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        // Logo
                        const Icon(
                          Icons.eco,
                          size: 64,
                          color: Color(0xFF2E7D32),
                        ),
                        const SizedBox(height: 16),
                        const Text(
                          'SmartAgri IoT',
                          style: TextStyle(
                            fontSize: 24,
                            fontWeight: FontWeight.bold,
                            color: Color(0xFF2E7D32),
                          ),
                        ),
                        const SizedBox(height: 32),
                        
                        // Username Field
                        TextFormField(
                          controller: _usernameController,
                          decoration: InputDecoration(
                            labelText: 'Username',
                            prefixIcon: const Icon(Icons.person),
                            border: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                          ),
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return 'Username tidak boleh kosong';
                            }
                            return null;
                          },
                        ),
                        const SizedBox(height: 16),
                        
                        // Password Field
                        TextFormField(
                          controller: _passwordController,
                          obscureText: _obscurePassword,
                          decoration: InputDecoration(
                            labelText: 'Password',
                            prefixIcon: const Icon(Icons.lock),
                            suffixIcon: IconButton(
                              icon: Icon(
                                _obscurePassword 
                                  ? Icons.visibility_off 
                                  : Icons.visibility,
                              ),
                              onPressed: () {
                                setState(() {
                                  _obscurePassword = !_obscurePassword;
                                });
                              },
                            ),
                            border: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                          ),
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return 'Password tidak boleh kosong';
                            }
                            return null;
                          },
                        ),
                        const SizedBox(height: 24),
                        
                        // Error Message
                        Consumer<AuthProvider>(
                          builder: (context, auth, child) {
                            if (auth.errorMessage != null) {
                              return Padding(
                                padding: const EdgeInsets.only(bottom: 16),
                                child: Text(
                                  auth.errorMessage!,
                                  style: const TextStyle(color: Colors.red),
                                ),
                              );
                            }
                            return const SizedBox.shrink();
                          },
                        ),
                        
                        // Login Button
                        Consumer<AuthProvider>(
                          builder: (context, auth, child) {
                            return SizedBox(
                              width: double.infinity,
                              height: 48,
                              child: ElevatedButton(
                                onPressed: auth.isLoading ? null : _handleLogin,
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: const Color(0xFF2E7D32),
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(12),
                                  ),
                                ),
                                child: auth.isLoading
                                  ? const CircularProgressIndicator(color: Colors.white)
                                  : const Text(
                                      'Login',
                                      style: TextStyle(
                                        fontSize: 16,
                                        color: Colors.white,
                                      ),
                                    ),
                              ),
                            );
                          },
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }

  Future<void> _handleLogin() async {
    if (_formKey.currentState!.validate()) {
      final auth = Provider.of<AuthProvider>(context, listen: false);
      final success = await auth.login(
        _usernameController.text,
        _passwordController.text,
      );
      
      if (success && mounted) {
        Navigator.pushReplacementNamed(context, '/home');
      }
    }
  }

  @override
  void dispose() {
    _usernameController.dispose();
    _passwordController.dispose();
    super.dispose();
  }
}
```

---

## Step 6: Setup Main App dengan Provider

### 6.1 Update `lib/main.dart`
```dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'providers/auth_provider.dart';
import 'screens/login_screen.dart';
import 'screens/home_screen.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
      ],
      child: MaterialApp(
        title: 'SmartAgri IoT',
        theme: ThemeData(
          colorScheme: ColorScheme.fromSeed(seedColor: const Color(0xFF2E7D32)),
          useMaterial3: true,
        ),
        initialRoute: '/login',
        routes: {
          '/login': (context) => const LoginScreen(),
          '/home': (context) => const HomeScreen(),
        },
      ),
    );
  }
}
```

---

## Step 7: Testing

### 7.1 Jalankan Laravel Server
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

// turbo
### 7.2 Jalankan Flutter App
```bash
flutter run
```

### 7.3 Test Login
1. Buka app Flutter
2. Masukkan username dan password yang valid
3. Pastikan berhasil login dan redirect ke Home Screen

---

## Troubleshooting

### Error: Connection Refused
- Pastikan Laravel server berjalan
- Untuk emulator Android, gunakan `10.0.2.2` bukan `localhost`
- Untuk real device, gunakan IP komputer (contoh: `192.168.1.100`)

### Error: CORS
Tambahkan di `app/Http/Kernel.php` middleware `api`:
```php
\Illuminate\Http\Middleware\HandleCors::class,
```

### Error: Token Invalid
Pastikan User model sudah menggunakan `HasApiTokens` trait
