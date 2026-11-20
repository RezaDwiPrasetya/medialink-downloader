# 02. Problem Statement

## Problem Statement
> Pengguna lokal (pelajar/developer) mengalami kesulitan mengunduh video atau foto dari internet secara aman dan bersih ketika ingin mengarsip konten untuk keperluan pribadi/edukasi, yang menyebabkan mereka bergantung pada situs downloader pihak ketiga yang penuh iklan, berpotensi menyebarkan malware, dan tidak dapat dipercaya.

## Target User
Pengguna komputer lokal (pelajar, developer, archivist pribadi) yang ingin menyimpan konten media milik sendiri atau konten berlisensi bebas dari internet ke penyimpanan lokal mereka.

## Context
Terjadi setiap kali pengguna menemukan video edukasi, klip tutorial, atau foto yang ingin disimpan untuk diakses offline, baik dari URL berkas langsung maupun dari platform seperti YouTube, TikTok, atau Instagram.

## Pain Points
1. Situs downloader pihak ketiga penuh iklan, popup, dan berisiko menyebarkan malware atau mencuri data.
2. `yt-dlp` di terminal sangat powerful tapi kurang ramah bagi pengguna non-teknis dan tidak ada antarmuka grafis.
3. Tidak ada cara mudah untuk melacak riwayat file apa saja yang sudah diunduh.

## Root Cause
Tidak ada tools lokal yang menggabungkan antarmuka web yang bersih, aman, dan mudah digunakan untuk mengunduh media dari berbagai jenis sumber URL — semuanya tersebar antara CLI (terlalu teknis) atau situs online (tidak aman).

## Evidence
| Evidence | Source | Finding |
|---|---|---|
| Situs downloader dihindari pengguna | Observasi langsung | Pengguna takut terinfeksi malware dari iklan situs downloader |
| yt-dlp punya kurva belajar tinggi | Dokumentasi & forum | Pengguna non-teknis kesulitan menggunakannya di CLI |
| Tidak ada riwayat unduhan | Observasi langsung | Pengguna lupa file apa saja yang sudah pernah diunduh |

## Existing Solution
- **Situs downloader online (savefrom, y2mate, dll.):** Berisiko, penuh iklan.
- **yt-dlp langsung di terminal:** Aman tapi tidak ramah pengguna dan tidak ada GUI.
- **IDM (Internet Download Manager):** Berbayar dan tidak mendukung semua platform.

## Impact
- **Time:** Waktu terbuang untuk mencari situs downloader yang aman dan bebas iklan.
- **Security:** Risiko malware dari situs-situs downloader tidak tepercaya.
- **Operational:** Tidak ada pencatatan riwayat sehingga file ganda sering terjadi.

## Validation Summary
Pengguna memerlukan tools lokal yang terpercaya, bebas iklan, dan mudah digunakan — sesuatu yang bisa dipakai tanpa harus membuka terminal dan tanpa risiko keamanan dari situs luar.
