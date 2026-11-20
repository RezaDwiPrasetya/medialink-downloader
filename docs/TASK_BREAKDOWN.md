# Task Breakdown — MediaLink Downloader

> **Panduan membaca dokumen ini:**
>
> - Setiap task ditulis sesuai aturan: **Specific** (jelas), **Small** (maks. 1 hari), dan **Testable** (ada cara mengecek selesai atau belum).
> - Setiap task punya referensi ke dokumen Wiki terkait (FR, US, F-ID).
> - Status centang `- [x]` untuk menandai task selesai.
> - Kerjakan satu sprint hingga **Definition of Done (DoD)** terpenuhi sebelum lanjut ke sprint berikutnya.

---

## Sprint 0 — Environment & Dokumentasi

**Sprint Goal:** Semua tool tersedia di PATH, proyek terbuka di browser via Flock, dan dokumentasi produk siap.

| Task | Referensi                  | Status     |
| ---- | -------------------------- | ---------- |
| T0.1 | 12-Technical-Specification | ✅ Selesai |
| T0.2 | 12-Technical-Specification | ✅ Selesai |
| T0.3 | 12-Technical-Specification | ✅ Selesai |
| T0.4 | 12-Technical-Specification | ✅ Selesai |
| T0.5 | 15-Release-Plan            | ✅ Selesai |
| T0.6 | 15-Release-Plan            | ✅ Selesai |
| T0.7 | 12-Technical-Specification | 🔲 Belum   |
| T0.8 | 12-Technical-Specification | 🔲 Belum   |

### Detail Task

- [X] **T0.1 — Verifikasi PHP 8.5 via Flock aktif dan bisa dipanggil dari terminal VS Code.**

  - _Selesai jika:_ `php -v` di terminal menampilkan versi PHP 8.5.x dari path Flock.
- [X] **T0.2 — Verifikasi Composer tersedia dari terminal VS Code.**

  - _Selesai jika:_ `composer -V` menampilkan versi Composer 2.x.
- [X] **T0.3 — Proyek Laravel 12 berhasil dibuat dan terbuka di browser via domain Flock.**

  - _Selesai jika:_ `http://medialink-downloader.test` membuka halaman welcome Laravel tanpa error.
- [X] **T0.4 — Repository GitHub dibuat dengan visibilitas Public.**

  - _Selesai jika:_ URL `https://github.com/RezaDwiPrasetya/medialink-downloader` dapat dibuka di browser.
- [X] **T0.5 — Kode awal (inisialisasi Laravel) berhasil di-push ke branch `main`.**

  - _Selesai jika:_ File proyek Laravel muncul di halaman repository GitHub.
- [X] **T0.6 — GitHub Wiki diisi dengan 15 dokumen Product Wiki (Home, 01–15).**

  - _Selesai jika:_ Tab Wiki di repository GitHub menampilkan semua 15 halaman yang bisa dibuka dan dibaca.
- [ ] **T0.7 — Pasang yt-dlp dan verifikasi bisa dipanggil dari terminal VS Code.**

  - _Cara pasang (Windows):_ Unduh `yt-dlp.exe` dari [github.com/yt-dlp/yt-dlp/releases](https://github.com/yt-dlp/yt-dlp/releases), simpan di folder yang ada di PATH (misal `C:\tools\`), lalu tambahkan foldernya ke PATH Windows.
  - _Selesai jika:_ `yt-dlp --version` di terminal menampilkan nomor versi tanpa error.
  - _Catatan:_ Task ini **sengaja ditunda** dan akan dikerjakan tepat sebelum Sprint 4 dimulai (prinsip Just-In-Time).
- [ ] **T0.8 — Pasang FFmpeg dan verifikasi bisa dipanggil dari terminal VS Code.**

  - _Cara pasang (Windows):_ Unduh dari [ffmpeg.org/download.html](https://ffmpeg.org/download.html), ekstrak, lalu tambahkan folder `bin/` ke PATH Windows.
  - _Selesai jika:_ `ffmpeg -version` di terminal menampilkan informasi versi tanpa error.
  - _Catatan:_ Task ini **sengaja ditunda** dan akan dikerjakan tepat sebelum Sprint 4 dimulai (prinsip Just-In-Time).

### Definition of Done Sprint 0

- [X] PHP 8.5, Composer, Laravel 12, Flock, Git, GitHub siap.
- [X] GitHub Wiki lengkap terisi.
- [ ] yt-dlp dan FFmpeg tersedia di PATH _(ditunda ke Sprint 4)_.s

---

## Sprint 1 — Route, Form, Validasi & SSRF Guard

**Sprint Goal:** Pengguna bisa membuka halaman utama dengan form input URL, dan sistem berhasil menolak URL yang tidak valid atau berbahaya dengan pesan yang jelas.

**Branch Git:** `feat/form-and-validation`

| Task | Referensi                                        | Status   |
| ---- | ------------------------------------------------ | -------- |
| T1.1 | 11-Design-Guidelines, 12-Technical-Specification | 🔲 Belum |
| T1.2 | 14-API-Specification, 12-Technical-Specification | 🔲 Belum |
| T1.3 | 06-Product-Specification, FR-001                 | 🔲 Belum |
| T1.4 | FR-001, FR-002, US-001, F-001                    | 🔲 Belum |
| T1.5 | FR-003, US-002, F-002                            | 🔲 Belum |
| T1.6 | 14-API-Specification, FR-011                     | 🔲 Belum |
| T1.7 | US-001, US-002, FR-002, FR-003                   | 🔲 Belum |

### Detail Task

- [ ] **T1.1 — Buat base layout Blade (`resources/views/layouts/app.blade.php`) dengan Tailwind CSS via CDN, navbar sederhana berisi nama aplikasi dan link ke halaman Riwayat.**

  - _Selesai jika:_ File layout ada, Tailwind aktif (class seperti `bg-gray-900` atau `text-white` berefek di browser), dan link navigasi bisa diklik.
- [ ] **T1.2 — Daftarkan route `GET /` di `routes/web.php` dan buat `DownloadController` dengan method `index()` yang merender view `home.blade.php`.**

  - _Selesai jika:_ Membuka `http://medialink-downloader.test` menampilkan halaman utama tanpa error 404 atau 500.
- [ ] **T1.3 — Buat form input URL di `resources/views/home.blade.php` berisi: label "URL Media", input text dengan placeholder, tombol "Periksa URL", dan direktif `@csrf`.**

  - _Selesai jika:_ Form tampil di browser, semua elemen terlihat, dan inspeksi HTML menunjukkan token CSRF tersembunyi di dalam form.
- [ ] **T1.4 — Buat Form Request `app/Http/Requests/DownloadRequest.php` dengan aturan validasi: `required`, `url`, dan hanya mengizinkan skema `http` atau `https`.**

  - _Selesai jika:_ Mengirim form kosong menghasilkan pesan error "URL wajib diisi". Mengirim teks biasa (bukan URL) menghasilkan pesan error format tidak valid.
- [ ] **T1.5 — Buat Service Class `app/Services/UrlValidatorService.php` dengan method yang menerima URL, me-resolve hostname-nya ke IP, lalu menolak jika IP termasuk loopback (`127.x.x.x`), privat (`10.x`, `172.16–31.x`, `192.168.x`), atau link-local (`169.254.x.x`).**

  - _Selesai jika:_ Memanggil method dengan `http://localhost`, `http://192.168.1.1`, dan `http://127.0.0.1` masing-masing mengembalikan false (ditolak). Memanggil dengan `https://youtube.com` mengembalikan true (diterima).
- [ ] **T1.6 — Daftarkan route `POST /check` di `routes/web.php`, buat method `check()` di `DownloadController` yang memanggil `DownloadRequest` dan `UrlValidatorService`, lalu tampilkan pesan error yang sesuai di bawah form input jika URL ditolak.**

  - _Selesai jika:_ Mengirim URL `http://localhost` via form menampilkan pesan "URL mengarah ke alamat jaringan yang tidak diizinkan" di halaman yang sama tanpa crash.
- [ ] **T1.7 — Buat Feature Test `tests/Feature/UrlValidationTest.php` dengan minimal 5 kasus uji: (1) URL kosong ditolak, (2) URL bukan format URL ditolak, (3) `http://localhost` ditolak, (4) `http://192.168.1.1` ditolak, (5) `https://www.example.com` lolos.**

  - _Selesai jika:_ `php artisan test --filter UrlValidationTest` menampilkan semua test PASSED tanpa ada yang FAILED.

### Definition of Done Sprint 1

- [ ] Halaman utama dengan form terbuka di browser.
- [ ] URL kosong dan format tidak valid ditolak dengan pesan jelas.
- [ ] URL `localhost`, `127.x.x.x`, dan `192.168.x.x` ditolak dengan pesan jelas.
- [ ] URL publik yang valid lolos ke tahap berikutnya (tidak error).
- [ ] Semua 5 kasus uji di `UrlValidationTest` lulus.

---

## Sprint 2 — Jalur A: Unduh File Berkas Langsung

**Sprint Goal:** Pengguna bisa mengirim URL berkas media langsung (`.mp4`, `.jpg`), melihat preview info file (tipe, ukuran), dan mengunduhnya ke `storage/app/private`.

**Branch Git:** `feat/direct-download`

| Task | Referensi                     | Status   |
| ---- | ----------------------------- | -------- |
| T2.1 | FR-004, F-003                 | 🔲 Belum |
| T2.2 | FR-005, F-005                 | 🔲 Belum |
| T2.3 | FR-007, US-003, F-004         | 🔲 Belum |
| T2.4 | FR-005, FR-008, US-004, F-005 | 🔲 Belum |
| T2.5 | FR-008, F-007                 | 🔲 Belum |
| T2.6 | 14-API-Specification, FR-011  | 🔲 Belum |

### Detail Task

- [ ] **T2.1 — Buat `app/Services/RouteDetectorService.php` yang menerima URL dan mengembalikan jalur ('A' atau 'B') berdasarkan pencocokan host dengan allowlist platform yang ketat (`youtube.com`, `youtu.be`, `www.youtube.com`, `m.youtube.com`, `tiktok.com`, `www.tiktok.com`, `instagram.com`, `www.instagram.com`).**

  - _Selesai jika:_ URL `https://www.youtube.com/watch?v=abc` mengembalikan 'B'. URL `https://contoh.com/video.mp4` mengembalikan 'A'. URL `https://youtube.evil.com` mengembalikan 'A' (bukan 'B' — pencocokan ketat, bukan "mengandung kata youtube").
- [ ] **T2.2 — Di dalam `DownloadController@check()`, setelah SSRF guard lolos dan jalur terdeteksi 'A', lakukan request `HEAD` ke URL tersebut untuk membaca header `Content-Type` dan `Content-Length`. Tolak jika Content-Type bukan `video/*` atau `image/*`.**

  - _Selesai jika:_ URL ke `https://contoh.com/dokumen.pdf` menghasilkan pesan error "Tipe file ini tidak didukung". URL ke file `.mp4` publik menghasilkan response dengan info tipe dan ukuran.
- [ ] **T2.3 — Tampilkan card preview info media di `home.blade.php` setelah pemeriksaan berhasil. Card berisi: tipe file, perkiraan ukuran (dalam KB/MB), dan tombol "Unduh" yang hanya muncul jika pemeriksaan lolos.**

  - _Selesai jika:_ Setelah mengecek URL berkas `.mp4` yang valid, halaman menampilkan info tipe dan ukuran serta tombol "Unduh" berwarna yang bisa diklik.
- [ ] **T2.4 — Buat `app/Services/DirectDownloadService.php` yang melakukan streaming download menggunakan Laravel HTTP Client dengan timeout koneksi 60 detik, timeout total 300 detik, dan menghentikan streaming jika ukuran file melebihi 500 MB.**

  - _Selesai jika:_ Memanggil service dengan URL berkas `.mp4` publik menghasilkan file yang tersimpan. Memanggil dengan URL yang lebih dari 500 MB menghentikan proses dan melempar exception.
- [ ] **T2.5 — Implementasikan fungsi sanitasi nama file di `DirectDownloadService`: ambil nama file dari URL, hapus path traversal (`../`), karakter non-alfanumerik kecuali `.`, `-`, `_`, dan pastikan ekstensi file ada. Simpan ke `storage/app/private/`.**

  - _Selesai jika:_ URL dengan nama file `../../etc/passwd` menghasilkan nama simpan yang aman (misalnya `etcpasswd`). File tersimpan di path `storage/app/private/` dan tidak bisa diakses langsung via browser URL.
- [ ] **T2.6 — Daftarkan route `POST /download` di `routes/web.php`, buat method `download()` di `DownloadController` yang memanggil `DirectDownloadService`, lalu tampilkan notifikasi sukses (nama file dan ukuran) atau pesan error yang spesifik sesuai kasus kegagalan.**

  - _Selesai jika:_ Mengklik tombol "Unduh" setelah preview muncul menghasilkan notifikasi sukses di halaman, dan file fisik ada di folder `storage/app/private/`.

### Definition of Done Sprint 2

- [ ] File `.mp4` dan `.jpg` dari URL publik berhasil diunduh dan tersimpan di `storage/app/private`.
- [ ] File melebihi 500 MB dihentikan dengan pesan jelas.
- [ ] Content-Type bukan media (`text/html`, dll.) ditolak dengan pesan jelas.
- [ ] Nama file tersanitasi — tidak ada path traversal atau karakter berbahaya.
- [ ] File tidak bisa diakses langsung via URL browser (hanya lewat controller).

---

## Sprint 3 — Database & Riwayat Unduhan

**Sprint Goal:** Setiap proses unduhan (sukses maupun gagal) tercatat di database SQLite, dan pengguna bisa melihat seluruh riwayat di halaman History.

**Branch Git:** `feat/database-and-history`

| Task | Referensi                    | Status   |
| ---- | ---------------------------- | -------- |
| T3.1 | 13-Data-Model, FR-009        | 🔲 Belum |
| T3.2 | 13-Data-Model, FR-009        | 🔲 Belum |
| T3.3 | FR-009, F-008                | 🔲 Belum |
| T3.4 | FR-010, 14-API-Specification | 🔲 Belum |
| T3.5 | FR-010, US-006, F-009        | 🔲 Belum |
| T3.6 | FR-012, 14-API-Specification | 🔲 Belum |

### Detail Task

- [ ] **T3.1 — Buat dan jalankan migrasi `create_downloads_table` dengan kolom: `source_url` (text), `filename` (string, nullable), `file_path` (string, nullable), `file_size` (bigInteger, nullable), `media_type` (string, nullable), `route` (string — 'A' atau 'B'), `status` (string — 'pending', 'success', 'failed'), `error_message` (text, nullable), `downloaded_at` (timestamp, nullable).**

  - _Selesai jika:_ `php artisan migrate` berjalan tanpa error, dan tabel `downloads` ada di file `database/database.sqlite` (bisa dicek dengan perintah `php artisan tinker` → `Schema::hasTable('downloads')`).
- [ ] **T3.2 — Buat Model Eloquent `app/Models/Download.php` dengan properti `$fillable` berisi semua kolom yang bisa diisi, dan cast `downloaded_at` sebagai datetime.**

  - _Selesai jika:_ `Download::create([...])` di Tinker berhasil menyimpan record ke database tanpa error mass assignment.
- [ ] **T3.3 — Perbarui `DownloadController@download()` agar membuat record di tabel `downloads` dengan status `pending` saat proses dimulai, lalu memperbaruinya ke `success` (dengan `filename`, `file_path`, `file_size`, `downloaded_at`) atau `failed` (dengan `error_message`) setelah proses selesai.**

  - _Selesai jika:_ Setelah satu kali unduhan sukses, tabel `downloads` memiliki 1 record dengan `status = 'success'`. Setelah satu kali gagal (mis. URL tidak valid lolos ke sini), ada record dengan `status = 'failed'` dan `error_message` terisi.
- [ ] **T3.4 — Daftarkan route `GET /history` di `routes/web.php` dan buat `app/Http/Controllers/HistoryController.php` dengan method `index()` yang mengambil semua record dari tabel `downloads` diurutkan dari yang paling baru (`latest()`).**

  - _Selesai jika:_ Membuka `http://medialink-downloader.test/history` menampilkan halaman tanpa error 404 atau 500.
- [ ] **T3.5 — Buat `resources/views/history.blade.php` yang menampilkan tabel riwayat unduhan dengan kolom: URL Sumber (diperpendek jika terlalu panjang), Nama File, Tipe, Ukuran (dalam format MB/KB), Status (dengan warna berbeda untuk sukses/gagal/pending), dan Waktu Unduh. Sertakan empty state jika tabel kosong.**

  - _Selesai jika:_ Halaman riwayat menampilkan semua record yang ada. Jika kosong, tampil pesan "Belum ada riwayat unduhan."
- [ ] **T3.6 — Daftarkan route `GET /downloads/{id}/serve` dan buat method `serve()` di `HistoryController` yang memvalidasi bahwa ID ada di tabel `downloads`, lalu menyajikan file dari `storage/app/private` menggunakan `Storage::download()`.**

  - _Selesai jika:_ Mengakses URL `/downloads/1/serve` (jika ID 1 ada) mengunduh file ke browser. Mengakses ID yang tidak ada menghasilkan halaman 404.

### Definition of Done Sprint 3

- [ ] Tabel `downloads` ada di SQLite dengan semua kolom yang benar.
- [ ] Setiap unduhan sukses menghasilkan record `status = 'success'` yang lengkap.
- [ ] Setiap unduhan gagal menghasilkan record `status = 'failed'` dengan pesan error.
- [ ] Halaman riwayat menampilkan semua record dari yang terbaru.
- [ ] File bisa diunduh ulang lewat `/downloads/{id}/serve`.

---

## Sprint 4 — Jalur B: Unduh via yt-dlp

**Sprint Goal:** Pengguna bisa mengirim URL dari YouTube, TikTok, atau Instagram, melihat preview judul/durasi, dan mengunduhnya lewat yt-dlp yang dipanggil secara aman (tanpa shell injection).

**Branch Git:** `feat/ytdlp-integration`

> ⚠️ **Sebelum memulai Sprint ini**, selesaikan dulu **T0.7** (pasang yt-dlp) dan **T0.8** (pasang FFmpeg) dari Sprint 0.

| Task | Referensi                                 | Status   |
| ---- | ----------------------------------------- | -------- |
| T4.0 | T0.7, T0.8 dari Sprint 0                  | 🔲 Belum |
| T4.1 | FR-006, F-006, 12-Technical-Specification | 🔲 Belum |
| T4.2 | FR-006, FR-007, US-003                    | 🔲 Belum |
| T4.3 | FR-006, FR-008, US-005, F-006             | 🔲 Belum |
| T4.4 | FR-008, F-007                             | 🔲 Belum |
| T4.5 | FR-011, F-010, US-005                     | 🔲 Belum |

### Detail Task

- [ ] **T4.0 — Selesaikan T0.7 dan T0.8: pasang yt-dlp dan FFmpeg, verifikasi keduanya bisa dipanggil dari terminal VS Code.**

  - _Selesai jika:_ `yt-dlp --version` dan `ffmpeg -version` keduanya menampilkan versi tanpa error.
- [ ] **T4.1 — Buat `app/Services/YtDlpService.php` dengan constructor yang memvalidasi bahwa binary `yt-dlp` tersedia di PATH (menggunakan `which`/`where`). Buat method `getInfo(string $url)` yang memanggil `yt-dlp --dump-json --no-playlist --ignore-config` via `Symfony\Component\Process\Process` menggunakan array argumen (BUKAN string shell).**

  - _Selesai jika:_ Memanggil `getInfo('https://www.youtube.com/watch?v=dQw4w9WgXcQ')` mengembalikan array berisi key `title`, `duration`, `thumbnail`, dan `ext`. Proses dipanggil dengan `new Process(['yt-dlp', '--dump-json', ...])` — bukan `Process::fromShellCommandline(...)`.
- [ ] **T4.2 — Integrasikan `YtDlpService@getInfo()` ke dalam `DownloadController@check()` untuk URL Jalur B. Tampilkan card preview berisi: judul video, durasi (format menit:detik), thumbnail (jika ada), dan tombol "Unduh".**

  - _Selesai jika:_ Mengirim URL YouTube publik menampilkan card preview dengan judul dan durasi video yang benar sebelum tombol "Unduh" muncul.
- [ ] **T4.3 — Buat method `download(string $url, string $outputDir)` di `YtDlpService` yang memanggil yt-dlp untuk mengunduh video dengan argumen keamanan wajib: `--no-playlist`, `--max-filesize 500m`, `--socket-timeout 30`, `--ignore-config`, output template `%(id)s.%(ext)s`, dan timeout proses maksimal 120 detik.**

  - _Selesai jika:_ Memanggil method dengan URL YouTube publik menghasilkan file video di folder `storage/app/private/`. Proses yang melebihi 120 detik dihentikan paksa dan melempar exception.
- [ ] **T4.4 — Pastikan nama file output yt-dlp menggunakan template `%(id)s.%(ext)s` (bukan `%(title)s`) agar nama file tidak mengandung karakter aneh dari judul platform. Verifikasi file tersimpan di `storage/app/private/`.**

  - _Selesai jika:_ File hasil unduhan diberi nama menggunakan ID video (misal `dQw4w9WgXcQ.mp4`), bukan judul video yang bisa mengandung karakter berbahaya.
- [ ] **T4.5 — Tangani semua skenario kegagalan yt-dlp dan simpan ke database: (1) video dihapus/tidak tersedia → `error_message: "Video tidak tersedia"`, (2) video privat/butuh login → `error_message: "Konten ini bersifat privat"`, (3) format tidak didukung → `error_message: "Format tidak didukung"`, (4) timeout → `error_message: "Proses melebihi batas waktu"`.**

  - _Selesai jika:_ Mengirim URL video YouTube yang sudah dihapus menghasilkan record `status = 'failed'` di database dan pesan error yang ramah pengguna di halaman (bukan pesan mentah dari yt-dlp).

### Definition of Done Sprint 4

- [ ] yt-dlp dan FFmpeg tersedia di PATH dan terdeteksi oleh aplikasi.
- [ ] URL YouTube publik berhasil diunduh ke `storage/app/private`.
- [ ] URL TikTok publik berhasil diunduh ke `storage/app/private`.
- [ ] URL Instagram publik berhasil diunduh ke `storage/app/private`.
- [ ] URL privat/butuh login menghasilkan record `failed` dan pesan error yang jelas.
- [ ] yt-dlp dipanggil dengan Symfony Process array argumen — tidak ada string shell.
- [ ] Nama file menggunakan `%(id)s.%(ext)s` — bukan nama dari judul platform.

---

## Sprint 5 — Testing & Hardening

**Sprint Goal:** Semua acceptance criteria di MVP Scope terpenuhi, semua feature test lulus, dan aplikasi siap dipakai sebagai portofolio belajar.

**Branch Git:** `feat/testing-and-hardening`

| Task | Referensi                                    | Status   |
| ---- | -------------------------------------------- | -------- |
| T5.1 | 10-MVP-Scope                                 | 🔲 Belum |
| T5.2 | 07-User-Stories (semua AC)                   | 🔲 Belum |
| T5.3 | 12-Technical-Specification (Technical Risks) | 🔲 Belum |
| T5.4 | 11-Design-Guidelines (States)                | 🔲 Belum |
| T5.5 | 15-Release-Plan                              | 🔲 Belum |
| T5.6 | 15-Release-Plan                              | 🔲 Belum |

### Detail Task

- [ ] **T5.1 — Buat Feature Test end-to-end `tests/Feature/DownloadFlowTest.php` yang menguji: (1) alur lengkap Jalur A dari input hingga file tersimpan, (2) alur lengkap Jalur B dari input hingga file tersimpan, (3) riwayat tampil setelah unduhan sukses.**

  - _Selesai jika:_ `php artisan test` menampilkan semua test PASSED.
- [ ] **T5.2 — Periksa dan pastikan semua Acceptance Criteria dari US-001 hingga US-006 di `07-User-Stories.md` telah terpenuhi. Centang setiap AC yang sudah lolos.**

  - _Selesai jika:_ Semua checkbox AC di file `07-User-Stories.md` sudah dicentang.
- [ ] **T5.3 — Lakukan security review terhadap 3 risiko teknis di `12-Technical-Specification.md`: (1) pastikan tidak ada celah command injection di pemanggilan yt-dlp, (2) pastikan tidak ada path traversal di penyimpanan file, (3) pastikan SSRF guard menangani DNS rebinding.**

  - _Selesai jika:_ Ketiga risiko sudah diverifikasi mitigasinya dengan pengujian manual dan hasilnya didokumentasikan.
- [ ] **T5.4 — Pastikan semua state UI yang didefinisikan di `11-Design-Guidelines.md` sudah diimplementasikan: loading state saat request berjalan, empty state di halaman riwayat, error state dengan pesan spesifik, dan success state dengan notifikasi jelas.**

  - _Selesai jika:_ Semua state bisa dipicu dan terlihat dengan baik di browser.
- [ ] **T5.5 — Update yt-dlp ke versi terbaru dengan perintah `yt-dlp -U` dan verifikasi ulang bahwa Jalur B masih berjalan normal setelah update.**

  - _Selesai jika:_ `yt-dlp --version` menampilkan versi terbaru dan pengujian download YouTube/TikTok/IG masih berhasil.
- [ ] **T5.6 — Update `README.md` di root proyek dengan: deskripsi singkat aplikasi, prasyarat (PHP 8.5, Flock, yt-dlp, FFmpeg), langkah instalasi lokal, dan cara menjalankan test.**

  - _Selesai jika:_ Seseorang yang baru clone repo bisa mengikuti README dan menjalankan aplikasi tanpa perlu bertanya.

### Definition of Done Sprint 5 (= MVP Selesai)

- [ ] `php artisan test` menampilkan semua PASSED — tidak ada yang FAILED.
- [ ] Semua checklist di `10-MVP-Scope.md` sudah dicentang.
- [ ] Tidak ada bug kritis yang belum diselesaikan.
- [ ] README lengkap dan bisa diikuti oleh orang lain.
- [ ] yt-dlp sudah diperbarui ke versi terbaru.

---

## Ringkasan Status Keseluruhan

| Sprint   | Fokus                           | Status                            |
| -------- | ------------------------------- | --------------------------------- |
| Sprint 0 | Environment & Dokumentasi       | 🟡 Sebagian (T0.7 & T0.8 ditunda) |
| Sprint 1 | Form, Validasi & SSRF Guard     | 🔲 Belum                          |
| Sprint 2 | Jalur A: Download File Langsung | 🔲 Belum                          |
| Sprint 3 | Database & Riwayat              | 🔲 Belum                          |
| Sprint 4 | Jalur B: yt-dlp                 | 🔲 Belum                          |
| Sprint 5 | Testing & Hardening             | 🔲 Belum                          |
