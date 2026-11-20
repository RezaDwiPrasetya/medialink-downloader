# 05. Product Requirements Document

## Background
Proyek ini adalah latihan implementasi Laravel 12 untuk membangun aplikasi pengunduh media lokal yang aman. Inspirasi dari pengerjaan proyek PKL (Masjid Archive) yang membahas penyimpanan file, metadata database, dan validasi input — kini diaplikasikan pada kasus pengambilan file dari URL eksternal.

## Objective
Membangun aplikasi web lokal yang mampu:
1. Menerima URL dari pengguna dan memvalidasinya secara aman (Anti-SSRF).
2. Mendeteksi jenis sumber: file langsung (Jalur A) atau link platform sosial media (Jalur B).
3. Mengunduh file ke penyimpanan privat lokal.
4. Mencatat metadata unduhan ke database SQLite.

## Target User
Pengguna lokal tunggal di komputer Windows menggunakan browser — bukan aplikasi publik.

## User Needs
1. Bisa mengunduh file media dari URL langsung maupun link YouTube/TikTok/Instagram.
2. Mendapat umpan balik yang jelas saat URL tidak valid, berbahaya, atau unduhan gagal.
3. Bisa melihat riwayat semua file yang sudah pernah diunduh.

## Functional Requirements
| ID | Requirement | Priority |
|---|---|---|
| FR-001 | Aplikasi menerima input URL dari form HTML | Must |
| FR-002 | Validasi format URL: hanya HTTP/HTTPS yang diizinkan | Must |
| FR-003 | SSRF guard: tolak localhost, IP privat, loopback, link-local | Must |
| FR-004 | Deteksi jalur: host di allowlist → Jalur B; lainnya → Jalur A | Must |
| FR-005 | Jalur A: request HEAD/GET terbatas, cek Content-Type dan ukuran | Must |
| FR-006 | Jalur B: panggil `yt-dlp --dump-json` untuk ambil info media | Must |
| FR-007 | Tampilkan preview info media (judul, ukuran/durasi, tipe) sebelum unduh | Should |
| FR-008 | Unduh file ke `storage/app/private` dengan nama file yang aman | Must |
| FR-009 | Simpan metadata riwayat ke tabel `downloads` di SQLite | Must |
| FR-010 | Halaman riwayat menampilkan daftar unduhan yang pernah dilakukan | Should |
| FR-011 | Pesan error yang jelas dan ramah pengguna untuk setiap kasus gagal | Must |
| FR-012 | File dapat diakses/diunduh kembali oleh user lewat controller (bukan URL publik) | Should |

## Non-Functional Requirements
- **Performance:** Proses `yt-dlp` dibatasi timeout maksimal 120 detik.
- **Security:** SSRF guard wajib aktif; Symfony Process digunakan (bukan shell string); file disimpan di `storage/app/private`.
- **Accessibility:** Tidak ada target aksesibilitas khusus untuk MVP — antarmuka teks sederhana sudah cukup.
- **Reliability:** Error jaringan, timeout, dan kegagalan yt-dlp harus ditangani dengan pesan yang informatif, bukan crash.

## MVP Requirements
- FR-001, FR-002, FR-003, FR-004, FR-005, FR-006, FR-008, FR-009, FR-011 wajib selesai di MVP.

## Acceptance Criteria
- URL dengan format tidak valid ditolak dengan pesan jelas.
- IP lokal/privat ditolak meskipun diformat sebagai URL yang terlihat valid.
- File dari URL langsung berhasil diunduh dan tersimpan di `storage/app/private`.
- Link YouTube/TikTok/Instagram publik berhasil diunduh lewat yt-dlp.
- Setiap unduhan berhasil meninggalkan record di tabel `downloads`.

## Out of Scope
- Download playlist atau multi-link.
- Konten yang memerlukan login / cookie / token.
- Bypass DRM, paywall, atau proteksi teknis apapun.
- Deploy ke server publik.
- Sistem antrian (queue) dan Redis.
