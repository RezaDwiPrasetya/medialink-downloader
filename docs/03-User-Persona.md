# 03. User Persona

## Primary Persona

### Name
Reza — Pelajar Developer

### Profile
- Age: 17–22 tahun
- Occupation: Pelajar SMK / Mahasiswa jurusan IT / Developer junior
- Location/context: Komputer lokal pribadi (Windows), tidak di-deploy ke internet
- Experience level: Mengerti dasar web, familiar dengan terminal, sedang belajar Laravel

### Goals
1. Menyimpan video tutorial, materi belajar, atau konten edukasi dari YouTube/TikTok ke komputer lokal untuk ditonton offline.
2. Mengunduh foto atau video milik sendiri dari platform sosial media sebagai backup arsip pribadi.
3. Memahami cara kerja download via URL langsung (berkas) maupun via link platform secara teknis sambil belajar Laravel.

### Pain Points
1. Situs downloader online penuh iklan dan popup — tidak aman dan mengganggu.
2. Menggunakan `yt-dlp` di CLI tiap kali butuh download terasa repetitif dan tidak efisien.
3. Tidak ada pencatatan riwayat — sering bingung apakah file sudah pernah diunduh atau belum.

### Current Behavior
Membuka situs-situs downloader online atau mengetikkan perintah `yt-dlp <url>` manual di terminal setiap ingin mengunduh konten.

### Needs
1. Antarmuka web lokal yang bersih dan mudah digunakan (input URL → klik unduh → selesai).
2. Jaminan keamanan bahwa aplikasi tidak meneruskan URL ke tempat yang berbahaya.
3. Riwayat unduhan yang tercatat agar tidak mengunduh file yang sama dua kali.

### Quote
> "Saya cuma mau simpan video tutorialnya ke laptop, tapi situs downloadnya malah minta izin kamera segala. Mending bikin sendiri aja."

## Secondary Persona
Tidak ada untuk MVP. Aplikasi ini dirancang eksklusif untuk penggunaan lokal mandiri (single user, localhost only).
