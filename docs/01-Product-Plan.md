# 01. Product Plan

## Product Vision
Menjadi alat bantu lokal yang aman dan terpercaya bagi siapapun yang ingin mengarsip konten media dari internet ke komputer pribadi mereka.

## Product Goal
Membangun MVP yang mampu mendeteksi jenis sumber link (file langsung atau platform), memvalidasi keamanannya, mengunduhnya, dan mencatat riwayatnya — semua berjalan di mesin lokal tanpa perlu koneksi ke server lain.

## Target User
Pengguna lokal (pelajar, developer, content archivist) yang ingin menyimpan video atau foto dari internet ke komputer sendiri secara aman tanpa aplikasi pihak ketiga yang tidak jelas.

## Problem
Tidak ada cara mudah, aman, dan bebas iklan/malware untuk mengunduh media dari URL langsung maupun platform sosial media ke penyimpanan lokal menggunakan tools yang terpercaya.

## Value Proposition
- Gratis, lokal, dan tidak mengirimkan data ke server mana pun.
- Mendukung link berkas langsung dan link platform (YouTube, TikTok, Instagram).
- Aman: dilengkapi proteksi SSRF, validasi host ketat, dan penamaan file yang bersih.
- Mencatat riwayat unduhan untuk referensi.

## Product Strategy
- Bangun di atas Laravel 12 sebagai kerangka yang sudah familiar dari PKL.
- Manfaatkan yt-dlp (tool open-source tepercaya) untuk link platform.
- Utamakan keamanan sejak awal (SSRF guard, Symfony Process — bukan shell string).

## MVP Goal
Membuktikan bahwa aplikasi bisa menerima URL dari pengguna, memvalidasinya secara aman, mengunduh filenya (Jalur A & B), dan menyimpan riwayatnya ke database lokal.

## Out of Scope
- Download playlist / multi-link sekaligus.
- Konten privat, login, atau penggunaan cookie.
- Deploy ke server publik.
- Pemrosesan video (crop, convert format selain gabungan audio+video oleh yt-dlp).
- Sistem antrian (queue) dan Redis.
- Autentikasi pengguna aplikasi.

## Success Metrics
| Metric | Target |
|---|---:|
| URL valid berhasil diunduh (Jalur A) | 100% dari kasus uji |
| URL platform publik berhasil diunduh (Jalur B) | ≥ 90% dari kasus uji |
| URL berbahaya / privat IP berhasil ditolak | 100% dari kasus uji |
| Riwayat unduhan tersimpan di database | 100% dari unduhan sukses |

## Timeline
| Milestone | Target | Status |
|---|---|---|
| Environment & Setup | Sprint 0 | ✅ Done |
| Route, Form, Validasi | Sprint 1 | 🔲 Belum |
| Jalur A (file langsung) | Sprint 2 | 🔲 Belum |
| Database & Riwayat | Sprint 3 | 🔲 Belum |
| Jalur B (yt-dlp) | Sprint 4 | 🔲 Belum |
| Testing & Hardening | Sprint 5 | 🔲 Belum |
