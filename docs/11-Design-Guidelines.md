# 11. Design Guidelines

## Design Principles
1. **Simple:** Satu fokus per halaman. Halaman utama untuk input & unduh; halaman riwayat untuk melihat history.
2. **Clear:** Setiap status sistem harus dikomunikasikan secara visual — loading, sukses, error, kosong.
3. **Consistent:** Gunakan komponen dan gaya yang seragam di seluruh halaman.
4. **Functional First:** MVP tidak membutuhkan tampilan yang mewah — prioritas adalah alur yang berfungsi dengan benar.

## UI Stack
- **Template Engine:** Laravel Blade
- **Styling:** Tailwind CSS (via CDN atau Vite)
- **Icons:** Heroicons (via inline SVG atau CDN)

## Color Palette (Rekomendasi)
| Peran | Warna |
|---|---|
| Background utama | `gray-950` / `gray-900` (dark) atau `white` (light) |
| Aksen / tombol utama | `blue-600` |
| Sukses | `green-600` |
| Error / peringatan | `red-600` |
| Teks sekunder | `gray-400` |

> Pilih antara light mode atau dark mode — konsisten di seluruh halaman.

## Typography
- **Font:** System font stack (sans-serif bawaan OS) — tidak perlu Google Fonts untuk MVP.
- **Heading halaman:** `text-2xl font-bold`
- **Label form:** `text-sm font-medium`
- **Pesan error/sukses:** `text-sm`

## Spacing
- Padding container halaman: `px-4 py-8` atau `max-w-2xl mx-auto`.
- Gap antar elemen form: `space-y-4`.
- Padding tombol: `px-6 py-2`.

## Components
| Komponen | Deskripsi |
|---|---|
| `form-url` | Form input URL dengan label, input text, dan tombol submit |
| `alert` | Kotak pesan untuk status sukses, error, atau info |
| `media-preview` | Card yang menampilkan info media: judul, tipe, ukuran/durasi |
| `download-table` | Tabel riwayat unduhan di halaman history |
| `loading-indicator` | Spinner atau teks "Sedang memproses..." saat request berjalan |

## States
Setiap komponen interaktif harus mempertimbangkan kondisi berikut:
- **Default:** Tampilan awal form kosong.
- **Loading:** Spinner atau disabled state saat request dikirim ke server.
- **Empty:** Halaman riwayat tanpa data — tampilkan pesan ajakan untuk mulai mengunduh.
- **Error:** Alert merah dengan pesan error yang spesifik.
- **Success:** Alert hijau dengan nama file dan link ke riwayat.

## Catatan MVP
Untuk MVP, tidak perlu animasi atau micro-interaction yang rumit. Yang terpenting adalah alur berfungsi dengan benar dan setiap status sistem terkomunikasikan dengan jelas kepada pengguna.
