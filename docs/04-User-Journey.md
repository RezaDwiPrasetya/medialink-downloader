# 04. User Journey

## Scenario

Reza menemukan video tutorial Laravel di YouTube dan ingin menyimpannya ke laptop untuk ditonton offline tanpa perlu koneksi internet.

| Stage               | User Action                                          | User Thought                                      | Pain/Friction                                    | Opportunity                                                           |
| ------------------- | ---------------------------------------------------- | ------------------------------------------------- | ------------------------------------------------ | --------------------------------------------------------------------- |
| **Trigger**   | Reza menemukan video tutorial dan ingin menyimpannya | "Saya mau simpan ini buat belajar offline nanti." | Tidak ada tools lokal yang langsung bisa dipakai | Sediakan form URL yang langsung bisa diakses di browser               |
| **Discovery** | Membuka `http://medialink-downloader.test`         | "Ini lebih bersih dari situs downloader biasa."   | Biasanya harus buka situs luar yang penuh iklan  | UI yang bersih tanpa distraksi                                        |
| **Action**    | Paste URL YouTube → klik **Periksa URL**      | "Oke, ada info video-nya muncul. Bagus."          | Tidak yakin apakah URL-nya valid atau aman       | Tampilkan preview info media (judul, durasi, thumbnail) sebelum unduh |
| **Download**  | Klik tombol **Unduh**                          | "Proses unduh berjalan, semoga berhasil."         | Tidak tahu progres unduhan                       | Tampilkan status loading / indikator proses                           |
| **Outcome**   | File tersimpan, riwayat muncul di halaman            | "File sudah ada. Saya bisa cek riwayatnya juga."  | —                                               | Tampilkan riwayat dengan nama file, ukuran, dan waktu unduh           |

## Key Opportunity

Tahap **Action → Download**: Pengguna perlu mendapat keyakinan bahwa URL-nya aman dan media-nya valid *sebelum* proses unduh dimulai. Ini mengurangi kecemasan dan mencegah pemborosan waktu tunggu jika URL ternyata tidak valid.

## Desired Journey

1. Buka browser → `http://medialink-downloader.test` langsung muncul.
2. Paste URL → klik **Periksa URL** → preview info media langsung muncul dalam hitungan detik.
3. Klik **Unduh** → proses berjalan dengan indikator yang jelas.
4. File tersimpan di komputer → notifikasi sukses muncul.
5. Riwayat unduhan tercatat dan bisa dilihat kapan saja.
