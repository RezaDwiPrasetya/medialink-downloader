# 13. Data Model

## Entities

### Download
Menyimpan metadata setiap percobaan unduhan — baik yang berhasil maupun yang gagal.

| Field | Type | Required | Keterangan |
|---|---|---|---|
| id | BIGINT (auto increment) | Yes | Primary key |
| source_url | TEXT | Yes | URL asli yang dimasukkan pengguna (dipotong jika mengandung query params sensitif) |
| filename | VARCHAR(255) | No | Nama file hasil unduhan yang sudah disanitasi (null jika gagal) |
| file_path | VARCHAR(500) | No | Path relatif dari `storage/app/private` (null jika gagal) |
| file_size | BIGINT | No | Ukuran file dalam bytes (null jika gagal) |
| media_type | VARCHAR(100) | No | Content-Type / tipe media (`video/mp4`, `image/jpeg`, dll.) |
| route | ENUM('A', 'B') | Yes | Jalur yang digunakan: A = berkas langsung, B = yt-dlp |
| status | ENUM | Yes | `pending`, `success`, `failed` |
| error_message | TEXT | No | Pesan error jika status = `failed` (null jika sukses) |
| downloaded_at | TIMESTAMP | No | Waktu unduhan selesai (null jika belum selesai) |
| created_at | TIMESTAMP | Yes | Waktu record dibuat (Laravel default) |
| updated_at | TIMESTAMP | Yes | Waktu record terakhir diupdate (Laravel default) |

## Relationships

```text
(Hanya ada satu entity untuk MVP)

downloads
  ← Dibuat oleh DownloadController
  ← Dibaca oleh HistoryController
```

> Tidak ada relasi antar tabel karena MVP tidak memiliki sistem autentikasi/user management. Semua unduhan dianggap milik satu pengguna lokal yang sama.

## Database Rules
- `source_url` jangan simpan URL yang mengandung token autentikasi atau kredensial.
- `file_path` hanya menyimpan path relatif dari `storage/app/private`, bukan path absolut sistem.
- `status` default `pending` saat record pertama kali dibuat, diupdate ke `success` atau `failed` setelah proses selesai.
- Jika unduhan gagal, `filename`, `file_path`, dan `file_size` boleh `null`.

## Notes
- Database menggunakan SQLite (file di `database/database.sqlite`) — cocok untuk penggunaan lokal tanpa perlu server database terpisah.
- Migrasi dibuat dengan `php artisan make:migration create_downloads_table`.
- Model `Download` menggunakan Eloquent dengan `$fillable` untuk mass assignment protection.
- Enum untuk `route` dan `status` didefinisikan di migration menggunakan `string` dengan validasi di level aplikasi (bukan DB-level enum, agar kompatibel dengan SQLite).
