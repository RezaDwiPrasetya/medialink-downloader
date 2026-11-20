# 14. API Specification

> Aplikasi ini adalah aplikasi web tradisional (server-rendered dengan Blade), bukan REST API. Semua endpoint menggunakan route **web** Laravel, bukan `routes/api.php`. Response berbentuk HTML redirect dengan session flash, bukan JSON.

## Base URL
`http://medialink-downloader.test`

---

## Endpoints (Web Routes)

### GET /
**Purpose:** Menampilkan halaman utama dengan form input URL.

**Response:** Halaman HTML (`home.blade.php`) dengan form kosong atau hasil pemeriksaan sebelumnya (via session).

---

### POST /check
**Purpose:** Memvalidasi URL, menjalankan SSRF guard, mendeteksi jalur, dan mengambil info media.

**Request (Form Data):**
```
url = "https://www.youtube.com/watch?v=abc123"
_token = "<csrf_token>"
```

**Response (Sukses):** Redirect ke `GET /` dengan session data:
```php
session([
  'preview' => [
    'title'    => 'Judul Video',
    'type'     => 'video/mp4',
    'duration' => '10:35',       // Jalur B
    'size'     => 1048576,       // bytes, Jalur A
    'route'    => 'B',
    'url'      => 'https://...',
  ]
])
```

**Response (Gagal):** Redirect ke `GET /` dengan session errors:
```php
// Contoh pesan error
"URL mengarah ke alamat jaringan yang tidak diizinkan."
"Format URL tidak valid. Hanya HTTP dan HTTPS yang diperbolehkan."
"Sumber ini bukan file media yang didukung."
```

---

### POST /download
**Purpose:** Menjalankan proses unduhan berdasarkan URL yang sudah lolos pemeriksaan `/check`.

**Request (Form Data):**
```
url    = "https://www.youtube.com/watch?v=abc123"
route  = "B"
_token = "<csrf_token>"
```

**Response (Sukses):** Redirect ke `GET /history` dengan flash message:
```php
session(['success' => 'File "abc123.mp4" (25.3 MB) berhasil diunduh.'])
```

**Response (Gagal):** Redirect ke `GET /` dengan flash message:
```php
session(['error' => 'Unduhan gagal: video tidak tersedia atau bersifat privat.'])
```

---

### GET /history
**Purpose:** Menampilkan halaman riwayat semua unduhan dari tabel `downloads`.

**Response:** Halaman HTML (`history.blade.php`) berisi tabel riwayat, diurutkan dari yang paling baru.

---

### GET /downloads/{id}/serve
**Purpose:** Menyajikan file yang sudah diunduh ke browser pengguna (untuk download ulang atau preview).

**Response:** File response dari `storage/app/private` via `Storage::download()`.

**Catatan Keamanan:**
- File tidak bisa diakses langsung via URL publik tanpa melewati route ini.
- ID divalidasi — pastikan record ada di tabel `downloads` sebelum menyajikan file.

---

## Error Handling (Session Flash)

| Kondisi | Pesan |
|---|---|
| URL kosong / format tidak valid | *"URL tidak valid. Pastikan URL dimulai dengan http:// atau https://"* |
| IP lokal / privat terdeteksi | *"URL mengarah ke alamat jaringan yang tidak diizinkan."* |
| Host di luar allowlist & bukan file langsung | *"Platform ini tidak didukung. Gunakan URL berkas langsung atau link dari YouTube, TikTok, atau Instagram."* |
| Content-Type bukan media | *"URL ini bukan link berkas media yang didukung."* |
| File melebihi batas ukuran | *"Ukuran file melebihi batas maksimum 500 MB."* |
| yt-dlp gagal (privat/dihapus) | *"Gagal mengunduh: video tidak tersedia, bersifat privat, atau tidak didukung."* |
| Timeout | *"Proses unduhan melebihi batas waktu. Coba lagi atau gunakan koneksi yang lebih stabil."* |
| Error server umum | *"Terjadi kesalahan saat memproses permintaan Anda. Silakan coba lagi."* |
