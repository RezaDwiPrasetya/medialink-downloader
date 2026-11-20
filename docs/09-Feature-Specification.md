# 09. Feature Specification

## Feature List

| ID | Feature | Priority | Status |
|---|---|---|---|
| F-001 | Form input URL dengan validasi | Must | 🔲 Belum |
| F-002 | SSRF Guard (proteksi IP lokal/privat) | Must | 🔲 Belum |
| F-003 | Deteksi jalur (Jalur A vs Jalur B) | Must | 🔲 Belum |
| F-004 | Preview info media sebelum unduh | Should | 🔲 Belum |
| F-005 | Download Jalur A (file berkas langsung) | Must | 🔲 Belum |
| F-006 | Download Jalur B (via yt-dlp) | Must | 🔲 Belum |
| F-007 | Penyimpanan file di storage/app/private | Must | 🔲 Belum |
| F-008 | Pencatatan riwayat ke database SQLite | Must | 🔲 Belum |
| F-009 | Halaman riwayat unduhan | Should | 🔲 Belum |
| F-010 | Penanganan error & pesan ramah pengguna | Must | 🔲 Belum |

---

## Feature Details

### F-001 — Form Input URL dengan Validasi

**Objective:** Menyediakan form yang bersih dan aman sebagai titik masuk utama aplikasi.

**User:** Pengguna lokal.

**Input:** String URL dari pengguna.

**Process:** Laravel Form Request memvalidasi URL (required, format URL, hanya HTTP/HTTPS).

**Output:** Jika valid, URL diteruskan ke pipeline pemeriksaan. Jika tidak, pesan error ditampilkan.

**Business Rules:**
- Wajib diisi, tidak boleh kosong.
- Harus format URL yang valid.
- Hanya protokol `http` dan `https`.

**Acceptance Criteria:**
- [ ] URL kosong menghasilkan pesan validasi.
- [ ] URL tanpa protokol ditolak.
- [ ] URL valid melewati validasi dan diteruskan ke F-002.

---

### F-002 — SSRF Guard

**Objective:** Memastikan aplikasi tidak bisa dijadikan proxy untuk mengakses resource internal komputer atau jaringan lokal.

**User:** Sistem (transparan ke pengguna).

**Input:** URL yang sudah lolos validasi format.

**Process:** Resolve DNS dari host URL → periksa apakah IP tujuan termasuk loopback, privat, link-local, atau reservasi lainnya. Validasi ulang setiap hop redirect.

**Output:** URL diizinkan atau ditolak dengan pesan error.

**Business Rules:**
- Tolak: `127.0.0.0/8`, `10.0.0.0/8`, `172.16.0.0/12`, `192.168.0.0/16`, `169.254.0.0/16`, `::1`.
- Tolak hostname: `localhost` dan variasinya.

**Acceptance Criteria:**
- [ ] `http://localhost/admin` ditolak.
- [ ] `http://192.168.1.1` ditolak.
- [ ] `http://127.0.0.1:8080` ditolak.
- [ ] URL publik yang valid lolos ke tahap berikutnya.

---

### F-005 — Download Jalur A (File Berkas Langsung)

**Objective:** Mengunduh file media dari URL berkas langsung ke penyimpanan privat lokal.

**User:** Pengguna lokal.

**Input:** URL berkas langsung yang sudah lolos SSRF guard dan bukan dari allowlist platform.

**Process:** Laravel HTTP Client melakukan streaming GET dengan batas ukuran dan timeout. Content-Type diperiksa; hanya `video/*` dan `image/*` yang diterima. File disimpan ke `storage/app/private` dengan nama yang disanitasi.

**Output:** File tersimpan di `storage/app/private/[nama_aman]`.

**Business Rules:**
- Batas ukuran: 500 MB.
- Timeout: 60 detik untuk koneksi, 300 detik total.
- Jika Content-Type bukan media, tolak.
- Nama file diambil dari URL dan disanitasi (strip path traversal, karakter aneh, dll.).

**Acceptance Criteria:**
- [ ] File `.mp4` publik berhasil diunduh dan tersimpan.
- [ ] File yang melebihi 500 MB dihentikan dan dihapus.
- [ ] Content-Type `text/html` atau `application/javascript` ditolak.
- [ ] Nama file tersanitasi (tidak ada `../` atau karakter berbahaya).

---

### F-006 — Download Jalur B (via yt-dlp)

**Objective:** Mengunduh video dari platform yang didukung menggunakan yt-dlp secara aman.

**User:** Pengguna lokal.

**Input:** URL platform (YouTube, TikTok, Instagram) yang sudah divalidasi.

**Process:** Symfony Process memanggil yt-dlp dengan array argumen (bukan string shell). Argumen wajib: `--no-playlist`, `--max-filesize 500m`, `--socket-timeout 30`, `--ignore-config`, output template `%(id)s.%(ext)s`.

**Output:** File video tersimpan di `storage/app/private`.

**Business Rules:**
- Hanya host di allowlist yang boleh ke Jalur B.
- Jangan gunakan opsi `--cookies`, `--exec`, atau shell injection.
- Timeout proses maksimal 120 detik.

**Acceptance Criteria:**
- [ ] Video YouTube publik berhasil diunduh.
- [ ] Video TikTok publik berhasil diunduh.
- [ ] Video Instagram publik berhasil diunduh.
- [ ] Video privat / butuh login menghasilkan pesan error yang jelas.
- [ ] Proses yt-dlp dihentikan paksa jika melebihi timeout.
