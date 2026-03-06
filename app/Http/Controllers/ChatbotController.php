<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Knowledge base chatbot - keyword matching
     */
    private array $responses = [
        // Sapaan
        [
            'keywords' => ['halo', 'hai', 'hello', 'hi', 'hey', 'selamat', 'assalamualaikum', 'pagi', 'siang', 'sore', 'malam'],
            'reply' => "Halo! 👋 Selamat datang di Swaratani IoT.\nSaya asisten virtual yang siap membantu Anda.\n\nApa yang bisa saya bantu hari ini?",
        ],
        // Monitoring
        [
            'keywords' => ['monitoring', 'monitor', 'lihat sensor', 'data sensor', 'cek sensor', 'sensor'],
            'reply' => "📊 **Cara Monitoring Sensor:**\n\n1. Login ke akun Anda\n2. Klik menu **Monitoring** di beranda\n3. Pilih device yang ingin dipantau\n4. Data sensor akan tampil real-time beserta grafik\n\n💡 Data diperbarui secara otomatis melalui sistem internet.",
        ],
        // Tambah Device
        [
            'keywords' => ['tambah device', 'add device', 'token', 'daftarkan device', 'pairing', 'hubungkan'],
            'reply' => "📱 **Cara Menambah Device:**\n\n1. Login ke akun Anda\n2. Buka halaman **Monitoring**\n3. Klik tombol **Tambah Device**\n4. Masukkan **Token** device (16 karakter) yang didapat dari Admin\n5. Beri nama kustom untuk device Anda\n6. Klik **Simpan**\n\n🔑 Token device bisa didapatkan dari Administrator sistem.",
        ],
        // Kontrol Output
        [
            'keywords' => ['kontrol', 'relay', 'output', 'nyala', 'matikan', 'on', 'off', 'switch', 'toggle'],
            'reply' => "🔌 **Cara Kontrol Output (Relay/Switch):**\n\n1. Buka halaman monitoring device\n2. Scroll ke bagian **Kontrol Output**\n3. Gunakan toggle switch untuk ON/OFF relay\n4. Untuk pompa, klik tombol dan atur parameter\n\n⚡ Perintah dikirim langsung ke device melalui jaringan internet.",
        ],
        // Pompa
        [
            'keywords' => ['pompa', 'pump', 'air baku', 'air pupuk', 'irigasi', 'siram'],
            'reply' => "💧 **Cara Kontrol Pompa:**\n\n1. Buka halaman monitoring device\n2. Temukan kartu pompa (Air Baku / Air Pupuk)\n3. Klik tombol pompa untuk mengatur\n4. Set durasi dan volume sesuai kebutuhan\n5. Klik **Kirim** untuk mengaktifkan\n\n⚠️ Pastikan device terhubung sebelum mengirim perintah.",
        ],
        // Jadwal
        [
            'keywords' => ['jadwal', 'schedule', 'timer', 'waktu', 'otomatis jam'],
            'reply' => "⏰ **Cara Mengatur Jadwal:**\n\n1. Buka halaman monitoring device\n2. Klik tombol **Jadwal** di header\n3. Tambahkan jadwal baru dengan mengatur:\n   - Waktu mulai & selesai\n   - Output yang diaktifkan\n   - Hari aktif\n4. Jadwal akan berjalan otomatis sesuai waktu yang diset\n\n📅 Jadwal disinkronkan langsung ke device.",
        ],
        // Otomasi
        [
            'keywords' => ['otomasi', 'automasi', 'automation', 'otomatis', 'auto', 'rule', 'aturan'],
            'reply' => "🤖 **Cara Mengatur Otomasi:**\n\n1. Buka halaman monitoring device\n2. Klik tombol **Otomasi** di header\n3. Buat rule baru:\n   - Pilih sensor pemicu (suhu, kelembapan, dll)\n   - Set kondisi (lebih dari / kurang dari)\n   - Pilih output yang diaktifkan\n4. Otomasi berjalan otomatis berdasarkan data sensor\n\n🔄 Sistem akan merespons otomatis saat kondisi terpenuhi.",
        ],
        // Login
        [
            'keywords' => ['login', 'masuk', 'sign in'],
            'reply' => "🔐 **Cara Login:**\n\n1. Buka halaman utama Swaratani IoT\n2. Klik menu **Login**\n3. Masukkan email dan password\n4. Klik **Masuk**\n\n📧 Pastikan email Anda sudah diverifikasi.",
        ],
        // Register
        [
            'keywords' => ['register', 'daftar', 'buat akun', 'sign up', 'registrasi'],
            'reply' => "📝 **Cara Daftar Akun:**\n\n1. Buka halaman utama Swaratani IoT\n2. Klik menu **Daftar Akun**\n3. Isi nama, email, dan password\n4. Klik **Daftar**\n5. Cek email untuk verifikasi akun\n6. Klik link verifikasi di email\n\n✅ Setelah verifikasi, Anda bisa login dan mulai monitoring.",
        ],
        // Export CSV
        [
            'keywords' => ['export', 'csv', 'download', 'unduh', 'data'],
            'reply' => "📥 **Cara Download Data CSV:**\n\n1. Buka halaman monitoring device\n2. Klik tombol **Download CSV** di header\n3. Pilih rentang waktu data yang diinginkan\n4. Klik **Export**\n5. File CSV akan otomatis terdownload\n\n📊 Data meliputi semua sensor yang tercatat.",
        ],
        // Report Bug
        [
            'keywords' => ['bug', 'error', 'masalah', 'lapor', 'report', 'rusak', 'tidak bisa', 'gagal', 'crash', 'hang', 'lambat', 'problem'],
            'reply' => "🐛 **Report Bug / Laporkan Masalah:**\n\nUntuk melaporkan bug, silakan sertakan informasi berikut:\n\n1. **Halaman** yang bermasalah\n2. **Langkah** untuk memunculkan bug\n3. **Pesan error** yang muncul (jika ada)\n4. **Perangkat** yang digunakan (HP/PC, browser)\n\n📧 Kirim laporan ke:\n**swaratani.iot@gmail.com**\n\nAtau hubungi Admin melalui sistem.\n\n⚡ Tim kami akan merespons secepat mungkin!",
        ],
        // Bantuan / Fitur
        [
            'keywords' => ['bantuan', 'help', 'fitur', 'bisa apa', 'apa saja', 'menu', 'fungsi'],
            'reply' => "ℹ️ **Fitur Swaratani IoT:**\n\n📊 **Monitoring** - Pantau sensor real-time\n📱 **Device** - Kelola device IoT\n🔌 **Kontrol** - Kontrol relay & pompa\n⏰ **Jadwal** - Atur jadwal otomatis\n🤖 **Otomasi** - Buat rule otomasi sensor\n📥 **Export** - Download data CSV\n👤 **Akun** - Login & register\n🐛 **Report Bug** - Laporkan masalah\n\nKetik topik yang ingin Anda ketahui lebih detail!",
        ],
        // Admin
        [
            'keywords' => ['admin', 'administrator', 'kelola', 'manage'],
            'reply' => "🛡️ **Panel Admin:**\n\nFitur admin tersedia untuk user dengan role Administrator:\n\n1. **Kelola Device** - Tambah, edit, hapus device IoT\n2. **Monitoring Semua Device** - Lihat data semua device\n3. **Kontrol Output** - Kontrol relay dari admin panel\n\n🔒 Akses admin diberikan oleh sistem administrator.",
        ],
        // Terima kasih
        [
            'keywords' => ['terima kasih', 'makasih', 'thanks', 'thank you', 'thx'],
            'reply' => "Sama-sama! 😊\nSenang bisa membantu. Jika ada pertanyaan lain, jangan ragu untuk bertanya ya! 🌱",
        ],
    ];

    /**
     * Handle chatbot message
     */
    public function respond(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $message = strtolower(trim($request->message));
        $reply = $this->findResponse($message);

        return response()->json([
            'reply' => $reply,
        ]);
    }

    /**
     * Find matching response based on keywords
     */
    private function findResponse(string $message): string
    {
        foreach ($this->responses as $entry) {
            foreach ($entry['keywords'] as $keyword) {
                if (str_contains($message, $keyword)) {
                    return $entry['reply'];
                }
            }
        }

        // Fallback response
        return "🤔 Maaf, saya belum mengerti pertanyaan Anda.\n\nCoba tanyakan tentang:\n• **Monitoring** sensor\n• **Tambah device**\n• **Kontrol** relay/pompa\n• **Jadwal** & otomasi\n• **Download** data CSV\n• **Login** & daftar akun\n\nAtau ketik **\"bantuan\"** untuk melihat semua fitur.";
    }
}
