# 06. Product Specification

## Feature
Pemeriksaan & Pengunduhan Media dari URL

## Objective
Memungkinkan pengguna memasukkan URL apa pun, mendapatkan validasi keamanan dan preview info media, lalu mengunduh file tersebut ke penyimpanan lokal dalam satu alur yang mulus.

## User
Pengguna lokal tunggal di `http://medialink-downloader.test`.

## User Story
> As a pengguna lokal, I want to memasukkan URL media dan mengunduhnya ke komputer saya, so that saya bisa menyimpan konten tersebut untuk diakses offline tanpa bergantung pada situs downloader pihak ketiga yang tidak aman.

## User Flow
1. Pengguna membuka halaman utama dan melihat form input URL.
2. Pengguna menempel (paste) URL dan menekan tombol **Periksa URL**.
3. Sistem memvalidasi URL dan mendeteksi jalurnya (A atau B).
4. Sistem menampilkan preview info media atau pesan error.
5. Pengguna menekan tombol **Unduh** (hanya muncul jika URL valid).
6. Sistem mengunduh file dan menyimpan metadata ke database.
7. Halaman menampilkan konfirmasi sukses dan link ke riwayat.

## Business Rules
- Hanya protokol HTTP dan HTTPS yang diizinkan.
- IP lokal, privat, loopback, dan link-local selalu ditolak (Anti-SSRF).
- Allowlist Jalur B: `youtube.com`, `youtu.be`, `www.youtube.com`, `m.youtube.com`, `tiktok.com`, `www.tiktok.com`, `instagram.com`, `www.instagram.com`.
- Batas ukuran file: 500 MB untuk Jalur A; batas diserahkan ke `yt-dlp --max-filesize` untuk Jalur B.
- Nama file hasil unduhan dibersihkan dan disanitasi — tidak menggunakan nama dari server/platform secara mentah.
- File disimpan di `storage/app/private`, **bukan** di folder `public`.

## UI Requirements
- Form input URL dengan placeholder yang jelas.
- Tombol **Periksa URL** yang terpisah dari tombol **Unduh**.
- Area preview info media (judul, tipe, ukuran/durasi) yang muncul setelah pemeriksaan.
- Tombol **Unduh** hanya muncul setelah URL lolos pemeriksaan.
- Pesan error yang spesifik (bukan hanya "Error terjadi").
- Indikator loading saat proses berjalan.

## Data Requirements
- Input: URL string dari pengguna.
- Output tersimpan: nama file, ukuran (bytes), tipe media, URL sumber, status, waktu unduhan.

## Acceptance Criteria
- [ ] URL tidak valid (bukan HTTP/HTTPS) ditolak dengan pesan jelas.
- [ ] URL yang mengarah ke IP lokal/privat ditolak.
- [ ] URL berkas langsung (`video/mp4`) berhasil diunduh ke `storage/app/private`.
- [ ] URL YouTube/TikTok/Instagram publik berhasil diunduh via yt-dlp.
- [ ] Tombol **Unduh** tidak muncul jika URL tidak lolos pemeriksaan.
- [ ] Metadata tersimpan di tabel `downloads` setelah unduhan sukses.

## Edge Cases
- URL redirect berantai (mis. URL shortener yang mengarah ke IP privat) → validasi setiap hop redirect.
- File berukuran sangat besar → hentikan streaming saat melebihi batas ukuran.
- yt-dlp gagal (video dihapus, privat, atau format tidak didukung) → tampilkan pesan error spesifik.
- Koneksi internet terputus di tengah unduhan → tangani dengan pesan timeout yang jelas.
