# 15. Release Plan

## Release Strategy
Proyek ini menggunakan pendekatan **Scrum ringan** dengan sprint mingguan. Setiap sprint memiliki satu fokus utama dan kriteria selesai (*Definition of Done*) yang jelas sebelum lanjut ke sprint berikutnya.

> **Prinsip:** Selesaikan satu sprint, jalankan, uji, dan pahami hasilnya. Jangan lanjut sebelum sprint sebelumnya benar-benar selesai dan dipahami.

---

## Sprint 0 — Environment & Dokumentasi
**Fokus:** Memastikan semua tool tersedia dan dokumentasi proyek siap.

- [x] PHP 8.5 via Flock terdeteksi dan berjalan.
- [x] Composer tersedia.
- [x] Proyek Laravel 12 berhasil dibuat.
- [x] Flock `link` dijalankan — proyek bisa dibuka di `http://medialink-downloader.test`.
- [x] Repository GitHub dibuat dan kode awal berhasil di-push.
- [ ] GitHub Wiki diisi (15 dokumen ini).
- [ ] yt-dlp terpasang dan bisa dipanggil dari terminal.
- [ ] FFmpeg terpasang dan bisa dipanggil dari terminal.

**Definition of Done:** Semua tool tersedia di PATH, proyek terbuka di browser via Flock, dan Wiki terisi lengkap.

---

## Sprint 1 — Route, Form, Validasi & SSRF Guard
**Fokus:** Membangun fondasi keamanan dan antarmuka dasar.

- [ ] Route `GET /` dan `POST /check` didefinisikan di `routes/web.php`.
- [ ] `DownloadController` dibuat dengan method `index()` dan `check()`.
- [ ] `DownloadRequest` (Form Request) untuk validasi URL.
- [ ] `UrlValidatorService` untuk SSRF guard (tolak IP lokal/privat).
- [ ] Halaman utama Blade dengan form input URL.
- [ ] Pesan error ditampilkan di view saat validasi gagal.
- [ ] Feature test untuk URL validator: kasus valid, kasus IP privat, kasus format salah.

**Definition of Done:** URL berbahaya/tidak valid ditolak dengan pesan jelas; URL valid lolos ke tahap berikutnya; semua test lulus.

---

## Sprint 2 — Jalur A: Unduh File Berkas Langsung
**Fokus:** Implementasi pengunduhan file dari URL langsung.

- [ ] `RouteDetectorService` mendeteksi Jalur A vs B.
- [ ] `DirectDownloadService` mengunduh file via Laravel HTTP Client (streaming, batas ukuran, cek Content-Type).
- [ ] File tersimpan di `storage/app/private` dengan nama yang disanitasi.
- [ ] Route `POST /download` diimplementasikan untuk Jalur A.
- [ ] Preview info (Content-Type, ukuran) ditampilkan sebelum tombol unduh muncul.
- [ ] Error (timeout, ukuran melebihi batas, Content-Type tidak didukung) ditangani dengan pesan yang jelas.

**Definition of Done:** File MP4 dan JPEG dari URL publik berhasil diunduh; file tersimpan di storage privat; semua kasus error ditangani.

---

## Sprint 3 — Database & Riwayat Unduhan
**Fokus:** Persistensi metadata dan halaman riwayat.

- [ ] Migrasi `create_downloads_table` dibuat dan dijalankan.
- [ ] Model `Download` dengan `$fillable` yang tepat.
- [ ] Record dibuat di awal unduhan (status `pending`) dan diupdate setelah selesai (`success` / `failed`).
- [ ] Route `GET /history` dan `HistoryController` dibuat.
- [ ] Halaman riwayat menampilkan semua record dari tabel `downloads`.
- [ ] Route `GET /downloads/{id}/serve` untuk mengakses file yang sudah diunduh.

**Definition of Done:** Setiap unduhan meninggalkan record di database; halaman riwayat menampilkan semua record dengan status yang benar.

---

## Sprint 4 — Jalur B: Unduh via yt-dlp
**Fokus:** Integrasi yt-dlp untuk link platform sosial media.

- [ ] `YtDlpService` memanggil yt-dlp via Symfony Process (array argumen).
- [ ] Info media (`--dump-json`) diambil dan ditampilkan sebagai preview.
- [ ] Unduhan yt-dlp berjalan dengan argumen keamanan lengkap: `--no-playlist`, `--max-filesize 500m`, `--socket-timeout 30`, `--ignore-config`.
- [ ] Output disimpan ke `storage/app/private` dengan template nama aman `%(id)s.%(ext)s`.
- [ ] Error yt-dlp (privat, dihapus, tidak didukung) ditangani dan dicatat ke database.
- [ ] Allowlist host divalidasi ketat (cocokkan subdomain yang sah).

**Definition of Done:** Link YouTube, TikTok, dan Instagram publik berhasil diunduh; link privat menghasilkan pesan error yang jelas; yt-dlp dipanggil via Symfony Process (bukan shell string).

---

## Sprint 5 — Testing & Hardening
**Fokus:** Memastikan semua kasus edge case tertangani dan aplikasi stabil.

- [ ] Feature test untuk seluruh alur (URL tidak valid, SSRF, Jalur A, Jalur B, riwayat).
- [ ] Review kode untuk potensi keamanan (command injection, path traversal, SSRF bypass).
- [ ] Update yt-dlp ke versi terbaru.
- [ ] Pastikan semua pesan error ramah pengguna dan informatif.
- [ ] README proyek diupdate dengan petunjuk instalasi dan penggunaan.

**Definition of Done:** Semua acceptance criteria di `10-MVP-Scope.md` terpenuhi; semua feature test lulus.

---

## Release Criteria (MVP Selesai)
- [ ] Semua item di Sprint 0–5 selesai.
- [ ] Semua acceptance criteria di MVP Scope terpenuhi.
- [ ] Tidak ada bug kritis yang belum diselesaikan.
- [ ] README dan Wiki dokumentasi lengkap dan up-to-date.

## Version
Current: `0.1.0-dev`

## Changelog
### 0.1.0-dev
- Inisialisasi proyek Laravel 12.
- Setup Flock environment.
- Repository GitHub dibuat.
- GitHub Wiki (dokumentasi produk) dibuat.
