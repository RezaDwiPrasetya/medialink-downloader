# 07. User Stories

## Epic: Validasi & Pemeriksaan URL

### US-001 — Validasi Format URL
**As a** pengguna lokal  
**I want** diinformasikan jika URL yang saya masukkan formatnya tidak valid  
**So that** saya tidak membuang waktu menunggu proses unduhan yang pasti akan gagal

#### Acceptance Criteria
- [ ] URL tanpa protokol (`http://` atau `https://`) ditolak dengan pesan: *"URL harus dimulai dengan http:// atau https://"*
- [ ] URL kosong ditolak dengan pesan validasi yang jelas
- [ ] URL yang valid secara format lanjut ke tahap SSRF check

#### Priority
Must

#### Related Feature
FR-001, FR-002

---

### US-002 — Perlindungan SSRF
**As a** pengguna lokal  
**I want** aplikasi menolak URL yang mengarah ke jaringan internal/lokal  
**So that** saya yakin aplikasi ini aman dan tidak bisa disalahgunakan untuk mengakses resource internal komputer saya

#### Acceptance Criteria
- [ ] URL yang mengarah ke `localhost`, `127.x.x.x`, `192.168.x.x`, `10.x.x.x` ditolak
- [ ] URL yang resolusi DNS-nya mengarah ke IP privat setelah redirect juga ditolak
- [ ] Pesan error jelas: *"URL mengarah ke alamat jaringan yang tidak diizinkan"*

#### Priority
Must

#### Related Feature
FR-003

---

## Epic: Pengunduhan Media

### US-003 — Preview Info Media Sebelum Unduh
**As a** pengguna lokal  
**I want** melihat informasi media (judul, durasi/ukuran, tipe file) sebelum saya mengklik unduh  
**So that** saya bisa memastikan ini file yang benar sebelum menunggu proses unduhan

#### Acceptance Criteria
- [ ] Jalur A: tampilkan Content-Type dan perkiraan ukuran file
- [ ] Jalur B: tampilkan judul video, durasi, dan thumbnail dari yt-dlp
- [ ] Tombol **Unduh** hanya aktif setelah preview berhasil dimuat

#### Priority
Should

#### Related Feature
FR-005, FR-006, FR-007

---

### US-004 — Unduh File Berkas Langsung (Jalur A)
**As a** pengguna lokal  
**I want** mengunduh file dari URL berkas langsung (mis. `.mp4`, `.jpg`)  
**So that** saya bisa menyimpan file tersebut ke komputer lokal saya

#### Acceptance Criteria
- [ ] File berhasil diunduh dan tersimpan di `storage/app/private`
- [ ] Nama file disanitasi sebelum disimpan
- [ ] Unduhan dihentikan jika melebihi batas ukuran (500 MB)
- [ ] Content-Type yang bukan media (`video/*`, `image/*`) ditolak

#### Priority
Must

#### Related Feature
FR-005, FR-008

---

### US-005 — Unduh dari Link Platform (Jalur B)
**As a** pengguna lokal  
**I want** mengunduh video dari YouTube, TikTok, atau Instagram  
**So that** saya bisa mengarsip konten edukasi/pribadi dari platform tersebut ke lokal

#### Acceptance Criteria
- [ ] Link YouTube publik berhasil diunduh via yt-dlp
- [ ] Link TikTok publik berhasil diunduh via yt-dlp
- [ ] Link Instagram publik berhasil diunduh via yt-dlp
- [ ] Link yang butuh login menghasilkan pesan error yang jelas
- [ ] yt-dlp dipanggil dengan Symfony Process (array argumen, bukan string shell)

#### Priority
Must

#### Related Feature
FR-006, FR-008

---

## Epic: Riwayat Unduhan

### US-006 — Melihat Riwayat Unduhan
**As a** pengguna lokal  
**I want** melihat daftar semua file yang pernah saya unduh  
**So that** saya tidak mengunduh file yang sama dua kali dan bisa melacak arsip saya

#### Acceptance Criteria
- [ ] Halaman riwayat menampilkan: URL sumber, nama file, ukuran, tipe, status, waktu unduh
- [ ] Status ditampilkan dengan jelas: `sukses`, `gagal`, atau `sedang proses`
- [ ] Data diurutkan dari yang paling baru

#### Priority
Should

#### Related Feature
FR-009, FR-010
