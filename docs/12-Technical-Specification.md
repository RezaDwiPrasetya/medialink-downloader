# 12. Technical Specification

## Technology Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Bahasa | PHP 8.5 |
| Environment Lokal | Flock (PHP-CGI, Caddy web server) |
| Template / View | Laravel Blade |
| Styling | Tailwind CSS |
| Database | SQLite (file: `database/database.sqlite`) |
| HTTP Client | Laravel HTTP Client (Jalur A) |
| Proses Eksternal | Symfony Process → yt-dlp + FFmpeg (Jalur B) |
| Version Control | Git + GitHub |
| Editor | Visual Studio Code |

## Architecture
Aplikasi menggunakan arsitektur **monolitik web standar Laravel** (MVC):
- **Routes** (`routes/web.php`) mendefinisikan endpoint.
- **Controller** menerima request, memanggil service/logic, dan mengembalikan response.
- **Service Class** mengenkapsulasi logika bisnis (validasi URL, SSRF guard, deteksi jalur, proses download).
- **Model** (`Download`) merepresentasikan data riwayat di database.
- **Blade View** merender antarmuka pengguna.

## Repository
- GitHub: `https://github.com/RezaDwiPrasetya/medialink-downloader`
- Branch utama: `main`

## Environment
- PHP: 8.5.x (dari Flock)
- Composer: ≥ 2.x
- Node.js: (opsional, hanya jika kompilasi Tailwind via Vite diperlukan)
- yt-dlp: Harus tersedia di PATH sistem (dipasang terpisah)
- FFmpeg: Harus tersedia di PATH sistem (dipasang terpisah)

## Application Structure
```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── DownloadController.php   ← Halaman utama & proses unduhan
│   │   └── HistoryController.php    ← Halaman riwayat
│   └── Requests/
│       └── DownloadRequest.php      ← Validasi form URL
├── Services/
│   ├── UrlValidatorService.php      ← Validasi format & SSRF guard
│   ├── RouteDetectorService.php     ← Deteksi Jalur A vs B
│   ├── DirectDownloadService.php    ← Jalur A: HTTP Client
│   └── YtDlpService.php             ← Jalur B: Symfony Process + yt-dlp
└── Models/
    └── Download.php                  ← Model riwayat unduhan

database/
├── migrations/
│   └── xxxx_create_downloads_table.php
└── database.sqlite

resources/views/
├── layouts/
│   └── app.blade.php                ← Layout utama
├── home.blade.php                   ← Halaman utama (form + preview)
└── history.blade.php                ← Halaman riwayat

routes/
└── web.php                          ← Definisi semua route
```

## Technical Decisions
| Decision | Reason |
|---|---|
| SQLite sebagai database | Tidak perlu instalasi MySQL/PostgreSQL; cukup untuk penyimpanan lokal MVP |
| Symfony Process (array args) untuk yt-dlp | Mencegah command injection — argumen tidak diteruskan ke shell sebagai string |
| File disimpan di `storage/app/private` | File tidak dapat diakses langsung via URL publik; wajib lewat controller |
| Laravel HTTP Client untuk Jalur A | Built-in di Laravel, mendukung streaming dan timeout dengan mudah |
| Flock sebagai environment | Menggantikan `php artisan serve`; mendukung domain `.test` dan HTTPS lokal |

## Technical Risks
- yt-dlp dapat berhenti bekerja sewaktu-waktu jika platform (YouTube/TikTok/IG) mengubah sistem mereka → mitigasi: update yt-dlp secara berkala.
- File besar yang diunduh via Jalur A bisa menghabiskan disk secara tidak terduga → mitigasi: batas ukuran wajib diterapkan saat streaming.
- SSRF via DNS rebinding → mitigasi: validasi IP tujuan setelah resolusi DNS, bukan hanya validasi hostname.
