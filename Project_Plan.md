ar


# MediaLink Downloader

## Dokumen Rencana Proyek

**Versi:** 1.1 (revisi: mendukung link platform lewat yt-dlp, khusus lokal)
**Framework:** Laravel 12
**PHP environment:** Flock
**Code editor:** Visual Studio Code
**Pendekatan:** Belajar bertahap, mulai dari MVP sederhana

---

## 1. Gambaran Umum

MediaLink Downloader adalah aplikasi web **lokal** untuk mengunduh video
atau foto dari sebuah link, lalu menyimpannya ke komputer sendiri. Ada
dua jenis sumber:

- **Jalur A, link berkas langsung** (contoh: `https://contoh.com/video.mp4`).
  Diunduh langsung memakai Laravel HTTP Client.
- **Jalur B, link halaman platform** (YouTube, TikTok, Instagram).
  Link seperti ini adalah halaman web, bukan berkas, sehingga
  Laravel tidak bisa mengunduhnya sendiri. Aplikasi memanggil alat
  eksternal **yt-dlp** (dan FFmpeg untuk menggabungkan video + audio)
  lewat Laravel.

Proyek ini adalah latihan vibe coding sebelum proyek akhir. Fokusnya
memahami alur input URL, validasi, keamanan, pemanggilan program
eksternal, proses unduh, dan pencatatan riwayat.

> **Batasan penggunaan (dibaca sebelum mulai):**
>
> - Aplikasi hanya dijalankan di komputer sendiri (`localhost`),
>   **tidak dipublikasikan** ke internet.
> - Gunakan hanya untuk konten milik sendiri, konten dengan izin
>   pemilik, atau konten yang lisensinya membolehkan.
> - Ketentuan layanan YouTube, TikTok, dan Instagram umumnya melarang
>   unduhan di luar fitur resmi mereka. Risikonya ada di pengguna
>   (misalnya pembatasan akun), jadi anggap ini eksperimen belajar.
> - Tidak ada fitur untuk melewati DRM, paywall, login, konten privat,
>   atau proteksi lain. Tidak memakai cookie/akun untuk mengakses
>   konten yang tidak publik. Jika sebuah link butuh login, hasilnya
>   "tidak didukung" dan itu dianggap benar.
> - Hasil unduhan tidak diunggah ulang atau disebarkan.

## 2. Tujuan Pembelajaran

Setelah MVP selesai, pengguna diharapkan memahami:

1. Struktur dasar proyek Laravel 12.
2. Routing, Controller, View, dan Blade.
3. Form HTML dan validasi input.
4. Penggunaan Laravel HTTP Client.
5. Dasar keamanan saat aplikasi menerima URL dari pengguna (SSRF,
   allowlist domain).
6. Memanggil program eksternal dengan aman lewat Symfony Process
   (tanpa shell injection).
7. Penyimpanan metadata sederhana menggunakan database.
8. Penanganan error, timeout, dan tampilan status proses.

## 3. Ruang Lingkup MVP

### Fitur yang termasuk

- Halaman utama dengan formulir URL.
- Validasi format URL dan pemeriksaan keamanan (SSRF).
- Deteksi jenis link: berkas langsung (Jalur A) atau platform yang
  didukung (Jalur B).
- Jalur A: pemeriksaan `Content-Type` dan ukuran, lalu unduh.
- Jalur B: ambil info media (judul, durasi, thumbnail) sebelum unduh,
  lalu unduh satu video/foto per link.
- Daftar platform yang diizinkan (allowlist): `youtube.com`,
  `youtu.be`, `tiktok.com`, `instagram.com`.
- Batas ukuran berkas dan batas waktu proses.
- Penamaan berkas hasil unduhan secara aman.
- Riwayat unduhan sederhana: URL sumber, nama berkas, ukuran, tipe,
  status, dan waktu.
- Pesan kesalahan yang mudah dipahami.

### Fitur yang belum termasuk

- Mengunduh playlist, akun, atau banyak link sekaligus.
- Konten privat, konten yang butuh login, dan penggunaan cookie.
- Mem-bypass DRM, paywall, atau pembatasan teknis.
- Pemrosesan video (pemotongan klip, konversi format) selain
  penggabungan video + audio oleh yt-dlp.
- Login dan manajemen akun aplikasi.
- Redis dan sistem queue (dipertimbangkan di tahap opsional).
- Integrasi AI.
- Deploy ke publik.

## 4. Teknologi

  Bagian               Pilihan

---

  Backend              Laravel 12
  Bahasa               PHP
  Environment PHP      Flock
  Editor               Visual Studio Code
  Tampilan             Blade + CSS sederhana atau Tailwind
  Database awal        SQLite
  HTTP request         Laravel HTTP Client (Jalur A)
  Program eksternal    yt-dlp + FFmpeg (Jalur B)
  Pemanggil program    Symfony Process (sudah ada di Laravel)
  Version control      Git + GitHub

Gunakan versi PHP yang kompatibel dengan Laravel 12 dan pastikan
terminal VS Code menggunakan PHP dari environment Flock yang sama dengan
yang dipakai untuk menjalankan proyek. yt-dlp dan FFmpeg harus bisa
dipanggil dari terminal yang sama (masuk `PATH`).

## 5. Rancangan Halaman

### Halaman utama

Komponen:

- Judul dan deskripsi singkat aplikasi, plus catatan batasan
  penggunaan.
- Input URL.
- Tombol **Periksa URL**.
- Area hasil pemeriksaan (jenis sumber, judul, durasi/ukuran,
  thumbnail jika ada).
- Tombol **Unduh** yang hanya muncul jika sumber lolos pemeriksaan.

### Halaman riwayat

Menampilkan: nama berkas, tipe media, ukuran, waktu unduh, status.

Untuk MVP, halaman utama dan riwayat boleh dibuat dalam satu halaman.

## 6. Alur Aplikasi

1. Pengguna memasukkan URL.
2. Aplikasi memvalidasi format URL (hanya HTTP/HTTPS).
3. Aplikasi memeriksa host: tolak `localhost`, IP privat, dan alamat
   jaringan internal.
4. Aplikasi menentukan jalur:
   - Host ada di allowlist platform → **Jalur B**.
   - Selain itu → **Jalur A** (harus berupa berkas langsung).
5. Aplikasi memeriksa sumber dengan batas waktu dan batas ukuran:
   - Jalur A: request `HEAD`/`GET` terbatas, cek `Content-Type`.
   - Jalur B: `yt-dlp --dump-json --no-playlist` untuk mengambil info.
6. Jika lolos, aplikasi menampilkan informasi media.
7. Pengguna menekan tombol unduh.
8. Aplikasi mengunduh berkas ke penyimpanan privat dan menyimpan
   metadata riwayat.
9. Aplikasi menampilkan hasil atau pesan kesalahan.

## 7. Catatan Keamanan Penting

Fitur yang mengambil URL dari pengguna berisiko menjadi celah
**Server-Side Request Forgery (SSRF)**. Memanggil program eksternal juga
menambah risiko **command injection**. Jangan langsung memproses URL
apa pun yang dikirim pengguna.

### Berlaku untuk semua jalur

- Hanya izinkan protokol HTTP dan HTTPS.
- Tolak `localhost`, loopback, IP privat, link-local, dan alamat
  jaringan internal.
- Tetapkan timeout dan batas ukuran berkas.
- Jangan mempercayai nama berkas dari server; bersihkan sebelum
  menyimpan.
- Simpan berkas di `storage/app/private`, **bukan** di folder
  `public`, dan sajikan lewat controller.
- Jangan menjalankan atau menampilkan berkas unduhan sebagai kode.
- Jangan menyimpan URL yang mengandung token atau kredensial ke
  riwayat.

### Khusus Jalur A (berkas langsung)

- Periksa hasil resolusi DNS dan alamat IP tujuan.
- Tangani redirect secara manual; setiap tujuan redirect divalidasi
  ulang.
- Cek batas ukuran **saat streaming**, jangan hanya percaya header
  `Content-Length`.
- Batasi tipe konten yang diterima (misalnya `video/*`, `image/*`).

### Khusus Jalur B (yt-dlp)

- **Allowlist host yang ketat.** Cocokkan host persis atau subdomain
  yang sah (`www.youtube.com`, `m.youtube.com`), jangan memakai
  pengecekan "mengandung kata youtube" karena `youtube.evil.com`
  akan lolos.
- Panggil yt-dlp dengan **array argumen** lewat Symfony Process
  (`new Process([...])`), bukan string shell.
- Selalu tambahkan `--no-playlist`, `--max-filesize`,
  `--socket-timeout`, dan batas waktu proses.
- Gunakan `--ignore-config` agar konfigurasi lain di komputer tidak
  ikut terbaca.
- Pakai template output yang aman, misalnya `%(id)s.%(ext)s`, bukan
  judul dari platform.
- Jangan memakai opsi `--cookies`, `--exec`, atau opsi lain yang
  tidak diperlukan.
- Perbarui yt-dlp secara berkala (`yt-dlp -U`), karena platform sering
  mengubah sistemnya dan unduhan bisa tiba-tiba gagal.

Jangan membuka aplikasi ke publik. Aplikasi ini hanya untuk komputer
sendiri.

## 8. Tahapan Pengerjaan

---

  Tahap                   Pekerjaan               Hasil

---

  0                       Memeriksa PHP,          Environment siap
                          Composer, Laravel,
                          yt-dlp, dan FFmpeg

  1                       Membuat proyek Laravel  Proyek dapat dijalankan
                          12 dan repo Git/GitHub

  2                       Membuat route dan       Form URL tampil
                          halaman utama Blade

  3                       Membuat validasi URL    Input tidak valid
                          dan pengecekan SSRF     ditolak

  4                       Membuat deteksi jalur   Jenis sumber dan info
                          (A/B) dan pemeriksaan   media tampil
                          sumber

  5                       Membuat unduhan Jalur A Berkas langsung dapat
                          (berkas langsung)       diunduh

  6                       Menambahkan database    Riwayat tersimpan
                          dan riwayat

  7                       Membuat unduhan Jalur B Link platform dapat
                          (yt-dlp lewat Symfony   diunduh ke lokal
                          Process)

  8                       Menguji error dan       MVP lebih aman dan
                          keamanan (termasuk      stabil
                          feature test validator)

9 (opsional)            Queue database dan      Unduhan besar tidak
                          indikator progres       memblokir halaman
-------------------------------------------------------------------

**Prinsip pengerjaan:** Selesaikan satu tahap, jalankan, uji, dan pahami
hasilnya sebelum lanjut ke tahap berikutnya. Jangan menyalin seluruh
implementasi sekaligus.

## 9. Kriteria Selesai MVP

MVP dianggap selesai jika:

- Aplikasi Laravel 12 berjalan melalui environment Flock.
- Halaman utama dapat dibuka.
- URL yang tidak valid ditolak dengan pesan yang jelas.
- URL yang mengarah ke alamat lokal/privat atau host di luar
  ketentuan ditolak.
- Berkas langsung yang memenuhi batasan dapat diperiksa dan diunduh.
- Link YouTube/TikTok/Instagram publik yang didukung dapat diperiksa
  dan diunduh ke komputer lokal.
- Link yang butuh login atau tidak didukung ditolak dengan pesan
  yang jelas.
- Riwayat unduhan tersimpan dan dapat ditampilkan.
- Error jaringan, tipe berkas tidak didukung, ukuran berlebih,
  timeout, dan kegagalan yt-dlp ditangani dengan baik.
- Feature test untuk validator URL lulus.

## 10. Cara Kolaborasi Saat Mengerjakan

- Kerjakan satu tahap per sesi.
- Sebelum menulis kode, jelaskan tujuan file dan konsep yang
  digunakan.
- Berikan kode secukupnya untuk tahap yang sedang dikerjakan.
- Jelaskan lokasi file dan perintah terminal secara spesifik, beserta
  penjelasan singkat tiap perintah.
- Setelah implementasi, berikan langkah pengujian.
- Jika muncul error, analisis error yang sebenarnya sebelum mengubah
  banyak file.
- Jangan menambahkan dependensi atau fitur yang belum diperlukan.

---

## Langkah Pertama

Mulai dengan memeriksa kesiapan environment di terminal VS Code:

```bash
php -v
composer -V
yt-dlp --version
ffmpeg -version
```

Pastikan `php` yang terdeteksi berasal dari folder `bin` PHP Flock,
bukan PHP XAMPP. Periksa juga bahwa `yt-dlp` dan `ffmpeg` terdeteksi.
Jika belum ada, pasang dulu sebelum lanjut. Jangan membuat proyek
sebelum hasil pemeriksaan ini jelas.
