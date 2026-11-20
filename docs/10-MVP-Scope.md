# 10. MVP Scope

## MVP Objective
Membuktikan bahwa aplikasi web lokal berbasis Laravel 12 bisa menerima URL dari pengguna, memvalidasinya secara aman (Anti-SSRF), mengunduh filenya baik dari berkas langsung (Jalur A) maupun dari platform sosial media (Jalur B via yt-dlp), dan mencatat riwayatnya di database SQLite — semuanya berjalan di lingkungan lokal melalui Flock tanpa deploy ke internet.

## Must Have
- Form input URL di halaman utama.
- Validasi format URL (HTTP/HTTPS saja).
- SSRF guard: tolak localhost, IP privat, loopback.
- Deteksi jalur: allowlist Jalur B vs Jalur A.
- Jalur A: cek Content-Type + ukuran, lalu download via HTTP Client.
- Jalur B: ambil info via `yt-dlp --dump-json`, lalu download via yt-dlp (Symfony Process).
- Simpan file ke `storage/app/private` dengan nama yang disanitasi.
- Simpan metadata ke tabel `downloads` di SQLite.
- Pesan error yang jelas untuk setiap skenario kegagalan.

## Should Have
- Preview info media (judul, ukuran/durasi, tipe) sebelum tombol unduh muncul.
- Halaman riwayat unduhan yang menampilkan semua record dari tabel `downloads`.
- Status unduhan: `sukses`, `gagal`, tercatat di database.

## Could Have
- Tombol hapus riwayat per item.
- Indikator progres unduhan yang lebih detail.
- Filter/search di halaman riwayat.

## Not Now
- Download playlist atau multi-URL sekaligus.
- Login/autentikasi pengguna aplikasi.
- Konten privat, cookie, bypass DRM/paywall.
- Deploy ke server publik atau cloud.
- Sistem queue (Redis, Laravel Horizon).
- Konversi atau pemrosesan video di luar yang dilakukan yt-dlp.
- Integrasi AI.

## MVP Core Flow
```
Input URL → Validasi Format → SSRF Guard → Deteksi Jalur →
Pemeriksaan Sumber → Preview Info → Konfirmasi Unduh →
Download ke Storage → Simpan Metadata → Tampilkan Hasil
```

## MVP Success Criteria
- [ ] Aplikasi berjalan di `http://medialink-downloader.test` via Flock tanpa `php artisan serve`.
- [ ] URL tidak valid ditolak dengan pesan yang jelas.
- [ ] URL mengarah ke IP lokal/privat ditolak.
- [ ] File berkas langsung (`video/mp4`, `image/jpeg`) berhasil diunduh ke `storage/app/private`.
- [ ] Link YouTube publik berhasil diunduh via yt-dlp.
- [ ] Link TikTok publik berhasil diunduh via yt-dlp.
- [ ] Link Instagram publik berhasil diunduh via yt-dlp.
- [ ] Link privat/butuh login ditolak dengan pesan error yang jelas.
- [ ] Setiap unduhan (sukses maupun gagal) tercatat di tabel `downloads`.
- [ ] Riwayat unduhan bisa dilihat di halaman riwayat.
- [ ] Feature test untuk URL validator lulus semua kasus.
