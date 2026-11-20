# 08. User Flow

## Core User Flow

```text
Pengguna membuka http://medialink-downloader.test
  ↓
Input URL di form → klik "Periksa URL"
  ↓
[Validasi Format URL]
  ├── ✗ Tidak valid → Tampilkan pesan error → Selesai
  ↓
[SSRF Guard: cek host & IP]
  ├── ✗ IP lokal/privat → Tampilkan pesan error → Selesai
  ↓
[Deteksi Jalur]
  ├── Host ada di allowlist → JALUR B (yt-dlp)
  └── Host tidak ada di allowlist → JALUR A (file langsung)
  ↓
[Pemeriksaan Sumber]
  ├── Jalur A: HEAD request → cek Content-Type & ukuran
  ├── Jalur B: yt-dlp --dump-json → ambil metadata
  ├── ✗ Gagal (timeout/format salah/privat) → Tampilkan pesan error → Selesai
  ↓
Tampilkan Preview Info Media (judul/tipe/ukuran/thumbnail)
  ↓
Pengguna klik "Unduh"
  ↓
[Proses Unduhan]
  ├── Jalur A: Stream download ke storage/app/private
  ├── Jalur B: yt-dlp download ke storage/app/private
  ├── ✗ Gagal (timeout/ukuran melebihi batas) → Tampilkan pesan error → Catat ke DB (status: gagal) → Selesai
  ↓
Simpan metadata ke tabel `downloads` (status: sukses)
  ↓
Tampilkan konfirmasi sukses + link ke halaman riwayat
  ↓
Pengguna Mendapat Nilai: File tersimpan aman di lokal
```

## Detailed Flow

1. **Entry Point:** Browser dibuka ke `http://medialink-downloader.test` — halaman utama dengan form URL langsung muncul.
2. **Input & Pemeriksaan:** Pengguna paste URL dan klik **Periksa URL**. Aplikasi memvalidasi format, cek SSRF, lalu mendeteksi jalur.
3. **Preview:** Jika lolos semua validasi, info media ditampilkan — pengguna bisa memutuskan apakah akan lanjut unduh.
4. **Unduhan:** Pengguna klik **Unduh**. File di-stream ke `storage/app/private` dengan nama yang sudah disanitasi.
5. **Pencatatan:** Metadata disimpan ke tabel `downloads` di SQLite — sukses maupun gagal keduanya dicatat.
6. **Konfirmasi:** Pengguna mendapat notifikasi hasil dan bisa melihat riwayat unduhan.

## Alternative Flows

### Error — URL Tidak Valid
Pengguna memasukkan URL yang bukan HTTP/HTTPS atau formatnya salah → validasi Laravel menolak sebelum request dikirim ke mana pun → pesan error ditampilkan di bawah form.

### Error — SSRF / Host Terlarang
URL yang resolusinya mengarah ke IP privat atau host di luar allowlist Jalur B tapi bukan file langsung → ditolak dengan pesan: *"Sumber ini tidak dapat diakses."*

### Error — Unduhan Gagal di Tengah Jalan
Koneksi terputus atau file melebihi batas ukuran saat streaming → proses dihentikan, file parsial dihapus, record di DB disimpan dengan status `gagal`.

### Empty State — Halaman Riwayat Kosong
Belum ada unduhan yang dilakukan → tampilkan pesan: *"Belum ada riwayat unduhan. Mulai dengan memasukkan URL di halaman utama."*

### Success State
File berhasil diunduh → notifikasi hijau muncul dengan nama file dan ukuran → link ke halaman riwayat tersedia.
