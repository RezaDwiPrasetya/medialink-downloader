# Wireframe — MediaLink Downloader

Dokumen ini memetakan tata letak (*layout*), hierarki informasi, dan alur interaksi antarmuka pengguna untuk **MediaLink Downloader** sebelum masuk ke tahap pengkodean (implementasi Blade & Tailwind).

---

## 1. Daftar Kebutuhan Wireframe (Checklist TASK 04)

- [x] **Entry / Landing:** Halaman utama yang langsung menampilkan identitas dan tujuan aplikasi.
- [x] **Main Screen:** Antarmuka bersih dengan form URL terpusat dan catatan batasan penggunaan.
- [x] **Core Feature:** Pengambilan media dari tautan web.
- [x] **Input / Action:** Form input URL, tombol *Periksa URL*, dan tombol *Unduh*.
- [x] **Result:** Card preview informasi media (judul, durasi/ukuran, tipe, thumbnail).
- [x] **Success State:** Notifikasi unduhan selesai disertai informasi file dan lokasi penyimpanan.
- [x] **Error State:** Pesan kesalahan kontekstual (validasi format, blokir SSRF, kegagalan unduh).
- [x] **Empty State:** Tampilan ramah saat riwayat unduhan masih kosong.

---

## 2. Struktur Tata Letak Global (Layout Shell)

Aplikasi menggunakan tata letak berbasis kontainer terpusat (*centered container*) dengan tema gelap/terang yang konsisten:

```text
+-----------------------------------------------------------------------------------+
|  [Logo] MediaLink Downloader                        [Beranda]  [Riwayat Unduhan]  |  <- Header / Navbar
+-----------------------------------------------------------------------------------+
|                                                                                   |
|                                                                                   |
|                                [ KONTEN UTAMA ]                                   |  <- Main Content Area
|                                (Maks. 640px - 768px)                              |     (Form / Preview / Tabel)
|                                                                                   |
|                                                                                   |
+-----------------------------------------------------------------------------------+
|  MediaLink Downloader - Lingkungan Lokal (Flock) | Khusus Penggunaan Edukasi      |  <- Footer
+-----------------------------------------------------------------------------------+
```

---

## 3. Detail Wireframe Layar & State

### Layar 1: Halaman Utama — Kondisi Awal (Default / Landing State)

Titik masuk utama saat pengguna membuka `http://medialink-downloader.test`.

```text
+-----------------------------------------------------------------------------------+
|  [>] MediaLink Downloader                                   [Beranda]  [Riwayat]  |
+-----------------------------------------------------------------------------------+
|                                                                                   |
|                            MediaLink Downloader                                   |
|               Unduh video & foto ke komputer lokal secara aman                    |
|                                                                                   |
|   +-- [i] Catatan Penggunaan -------------------------------------------------+   |
|   | Aplikasi ini berjalan lokal di komputer Anda. Gunakan hanya untuk konten  |   |
|   | milik sendiri atau yang berlisensi bebas. Tidak untuk konten privat/login.|   |
|   +---------------------------------------------------------------------------+   |
|                                                                                   |
|   Label: URL Media                                                                |
|   +---------------------------------------------------------------------------+   |
|   | Tempel link video atau gambar di sini (YouTube, TikTok, IG, atau berkas)  |   |
|   +---------------------------------------------------------------------------+   |
|                                                                                   |
|                               [  Periksa URL  ]                                   |
|                                                                                   |
+-----------------------------------------------------------------------------------+
|  MediaLink Downloader © 2026 - Localhost Environment                              |
+-----------------------------------------------------------------------------------+
```

---

### Layar 2: Kondisi Sedang Memeriksa (Loading State)

Saat pengguna menekan tombol **Periksa URL**, tombol dinonaktifkan (*disabled*) dan indikator proses muncul.

```text
+-----------------------------------------------------------------------------------+
|   Label: URL Media                                                                |
|   +---------------------------------------------------------------------------+   |
|   | https://www.youtube.com/watch?v=dQw4w9WgXcQ                               |   |
|   +---------------------------------------------------------------------------+   |
|                                                                                   |
|                    [ (o) Memeriksa keamanan & media... ]                          |
|                               (Tombol Disabled)                                   |
+-----------------------------------------------------------------------------------+
```

---

### Layar 3: Kondisi Error (Error State)

Muncul saat URL tidak valid atau melanggar aturan keamanan (misal: percobaan SSRF ke jaringan lokal).

#### Kasus A: Validasi Keamanan (SSRF Terdeteksi)
```text
+-----------------------------------------------------------------------------------+
|   Label: URL Media                                                                |
|   +---------------------------------------------------------------------------+   |
|   | http://192.168.1.1/router-login                                           |   |
|   +---------------------------------------------------------------------------+   |
|                                                                                   |
|   +-- [!] Akses Ditolak ------------------------------------------------------+   |
|   | URL mengarah ke alamat jaringan lokal/privat yang tidak diizinkan.        |   |
|   +---------------------------------------------------------------------------+   |
|                                                                                   |
|                               [  Periksa URL  ]                                   |
+-----------------------------------------------------------------------------------+
```

#### Kasus B: Format Tidak Valid
```text
+-----------------------------------------------------------------------------------+
|   Label: URL Media                                                                |
|   +---------------------------------------------------------------------------+   |
|   | www.contoh.com                                                            |   |
|   +---------------------------------------------------------------------------+   |
|   [!] Format URL tidak valid. Harus diawali dengan http:// atau https://          |
|                                                                                   |
|                               [  Periksa URL  ]                                   |
+-----------------------------------------------------------------------------------+
```

---

### Layar 4: Hasil Pemeriksaan / Preview (Result State)

Hanya muncul jika URL lolos validasi keamanan dan didukung. Tombol **Unduh** baru tersedia di sini.

#### Varian A: Jalur B (Platform — YouTube/TikTok/Instagram)
```text
+-----------------------------------------------------------------------------------+
|   +---------------------------------------------------------------------------+   |
|   | https://www.youtube.com/watch?v=dQw4w9WgXcQ                               |   |
|   +---------------------------------------------------------------------------+   |
|                               [  Periksa URL  ]                                   |
|                                                                                   |
|   +-- Media Ditemukan (Jalur B: YouTube) -------------------------------------+   |
|   | +------------------+   Judul   : Tutorial Dasar Laravel 12 untuk Pemula   |   |
|   | |                  |   Platform: YouTube                                  |   |
|   | |    [Thumbnail]   |   Durasi  : 14 menit 32 detik                        |   |
|   | |                  |   Format  : Video MP4                                |   |
|   | +------------------+                                                      |   |
|   |                                                                           |   |
|   |                     [  Unduh Media Sekarang  ]                            |   |
|   +---------------------------------------------------------------------------+   |
+-----------------------------------------------------------------------------------+
```

#### Varian B: Jalur A (Berkas Langsung — Direct File)
```text
+-----------------------------------------------------------------------------------+
|   +-- Media Ditemukan (Jalur A: Berkas Langsung) -----------------------------+   |
|   | Nama Berkas : presentasi-projek.mp4                                       |   |
|   | Tipe Konten : video/mp4                                                   |   |
|   | Ukuran      : 48.2 MB                                                     |   |
|   | Status Host : Publik & Aman                                               |   |
|   |                                                                           |   |
|   |                     [  Unduh Berkas Sekarang  ]                           |   |
|   +---------------------------------------------------------------------------+   |
+-----------------------------------------------------------------------------------+
```

---

### Layar 5: Unduhan Berhasil (Success State)

Muncul setelah file berhasil di-stream dan disimpan di folder privat lokal.

```text
+-----------------------------------------------------------------------------------+
|   +-- [V] Unduhan Berhasil Disimpan! -----------------------------------------+   |
|   |                                                                           |   |
|   | Berkas     : Tutorial Dasar Laravel 12.mp4                                |   |
|   | Ukuran     : 52.4 MB                                                      |   |
|   | Lokasi     : storage/app/private/dQw4w9WgXcQ.mp4                          |   |
|   | Tersimpan  : 20 Nov 2026, 13:45 WIB                                       |   |
|   |                                                                           |   |
|   |            [ Buka / Unduh Berkas ]    [ Lihat Riwayat ]                   |   |
|   +---------------------------------------------------------------------------+   |
|                                                                                   |
|                     [ <- Unduh Tautan Lainnya ]                                   |
+-----------------------------------------------------------------------------------+
```

---

### Layar 6: Halaman Riwayat Unduhan (History Screen)

Diakses lewat navigasi `[Riwayat]` (`http://medialink-downloader.test/history`).

```text
+-----------------------------------------------------------------------------------+
|  [>] MediaLink Downloader                                   [Beranda]  [Riwayat]  |
+-----------------------------------------------------------------------------------+
|                                                                                   |
|  Riwayat Unduhan Media                                                            |
|  Daftar semua media yang telah diproses di komputer ini                           |
|                                                                                   |
|  +-----------------------------------------------------------------------------+  |
|  | Waktu        | Sumber URL        | Nama Berkas    | Ukuran  | Status | Aksi |  |
|  +-----------------------------------------------------------------------------+  |
|  | 20/11 13:45  | youtube.com/...   | dQw4w9.mp4     | 52.4 MB | SUKSES | [Unduh] |
|  | 20/11 13:20  | cdn.site.com/..   | sample.jpg     |  1.8 MB | SUKSES | [Unduh] |
|  | 20/11 13:10  | tiktok.com/...    | (private-link) |    -    | GAGAL  | [Detail]|
|  +-----------------------------------------------------------------------------+  |
|                                                                                   |
|  [ <- Kembali ke Halaman Utama ]                                                  |
+-----------------------------------------------------------------------------------+
```

---

### Layar 7: Riwayat Kosong (Empty State)

Kondisi ketika database SQLite belum memiliki catatan unduhan sama sekali.

```text
+-----------------------------------------------------------------------------------+
|  Riwayat Unduhan Media                                                            |
|                                                                                   |
|  +-----------------------------------------------------------------------------+  |
|  |                                                                             |  |
|  |                             [ Ikon Arsip Kosong ]                           |  |
|  |                           Belum Ada Riwayat Unduhan                         |  |
|  |              Media yang Anda unduh akan tercatat otomatis di sini.          |  |
|  |                                                                             |  |
|  |                         [ Mulai Unduh Media Pertama ]                       |  |
|  |                                                                             |  |
|  +-----------------------------------------------------------------------------+  |
+-----------------------------------------------------------------------------------+
```

---

## 4. Evaluasi & Review Wireframe (Sesuai Panduan Mentor)

| Aspek Review | Pertanyaan Evaluasi | Analisis Desain Kita |
|---|---|---|
| **User** | Apakah pengguna tahu harus melakukan apa? | **Jelas.** Begitu halaman dibuka, hanya ada 1 kotak input URL dan 1 tombol aksi utama (*Periksa URL*). Tidak ada tombol yang membingungkan. |
| **Flow** | Apakah langkahnya terlalu banyak? | **Tidak.** Alurnya sangat pendek: `Tempel Link` $\rightarrow$ `Periksa` $\rightarrow$ `Klik Unduh`. Hanya 2 interaksi pengguna. |
| **Value** | Kapan pengguna mendapatkan nilai (*value*)? | 1. Di tahap **Preview**: pengguna mendapat kepastian bahwa link benar sebelum menunggu unduhan.<br>2. Di tahap **Success**: file tersimpan langsung di komputer lokal. |
| **Complexity** | Apakah ada langkah yang tidak perlu? | **Sangat minimal.** Tidak ada registrasi akun, tidak ada captcha palsu, dan tombol unduh sengaja disembunyikan sampai link lolos cek keamanan. |
| **Consistency**| Apakah pola UI konsisten? | **Konsisten.** Navbar selalu sama, tombol aksi utama konsisten, kotak peringatan menggunakan kode warna standar (Merah = Error, Hijau = Sukses, Biru = Info). |

---

## 5. Ringkasan Komponen UI untuk Tahap Koding (Blade & Tailwind)

1. **`Navbar`**: Logo sederhana + navigasi `Beranda` dan `Riwayat`.
2. **`NoticeBox`**: Kotak peringatan batasan penggunaan lokal.
3. **`UrlForm`**: Input text, proteksi CSRF, dan tombol submit.
4. **`ErrorAlert`**: Menampilkan pesan validasi atau blokir SSRF.
5. **`MediaPreviewCard`**: Menampilkan thumbnail, metadata media, dan tombol trigger download.
6. **`SuccessCard`**: Menampilkan konfirmasi file dan tautan penyimpanan.
7. **`HistoryTable`**: Tabel responsif untuk daftar rekaman riwayat.
8. **`EmptyStateCard`**: Tampilan informatif saat tabel kosong.
