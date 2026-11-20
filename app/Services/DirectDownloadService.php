<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DirectDownloadService
{
    /**
     * Batas ukuran maksimum berkas: 500 MB (dalam bytes).
     */
    public const MAX_FILE_SIZE = 500 * 1024 * 1024; // 524,288,000 bytes

    /**
     * Timeout koneksi dan stream.
     */
    public const TIMEOUT_SECONDS = 60;

    /**
     * Memeriksa header berkas media via HEAD request tanpa mengunduh seluruh file.
     *
     * @return array{content_type: string, content_length: ?int, filename: string, formatted_size: string}
     *
     * @throws Exception
     */
    public function inspect(string $url): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['User-Agent' => 'MediaLink-Downloader/1.0'])
                ->head($url);

            // Jika server menolak method HEAD (405 Method Not Allowed), fallback ke GET dengan header Range
            if ($response->status() === 405) {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'User-Agent' => 'MediaLink-Downloader/1.0',
                        'Range' => 'bytes=0-1024',
                    ])
                    ->get($url);
            }
        } catch (Exception $e) {
            throw new Exception('Gagal menghubungi server berkas: ' . $e->getMessage());
        }

        if (! $response->successful() && $response->status() !== 206) {
            throw new Exception("Server berkas merespons dengan status error HTTP {$response->status()}.");
        }

        // 1. Cek Content-Type
        $contentType = strtolower($response->header('Content-Type') ?? '');
        $isMedia = str_starts_with($contentType, 'video/')
            || str_starts_with($contentType, 'image/')
            || str_starts_with($contentType, 'audio/')
            || str_contains($contentType, 'octet-stream');

        if (! $isMedia && ! $this->hasMediaExtension($url)) {
            throw new Exception('URL ini bukan berkas media yang didukung (harus berupa video, gambar, atau audio).');
        }

        // 2. Cek Content-Length
        $contentLength = $response->header('Content-Length');
        $fileSize = is_numeric($contentLength) ? (int) $contentLength : null;

        if ($fileSize !== null && $fileSize > self::MAX_FILE_SIZE) {
            throw new Exception('Ukuran berkas melebihi batas maksimum 500 MB.');
        }

        $filename = $this->extractSafeFilename($url, $contentType);

        return [
            'content_type' => $contentType ?: 'video/mp4',
            'content_length' => $fileSize,
            'filename' => $filename,
            'formatted_size' => $this->formatBytes($fileSize),
        ];
    }

    /**
     * Mengunduh berkas langsung dan menyimpannya ke storage/app/private/downloads.
     *
     * @return array{filename: string, file_path: string, file_size: int, media_type: string}
     *
     * @throws Exception
     */
    public function download(string $url): array
    {
        // 1. Inspeksi terlebih dahulu
        $info = $this->inspect($url);

        // 2. Siapkan direktori privat
        $storageDir = 'downloads';
        if (! Storage::disk('local')->exists($storageDir)) {
            Storage::disk('local')->makeDirectory($storageDir);
        }

        // 3. Nama berkas unik dan aman
        $cleanName = pathinfo($info['filename'], PATHINFO_FILENAME);
        $extension = pathinfo($info['filename'], PATHINFO_EXTENSION) ?: 'mp4';
        $finalFilename = Str::slug($cleanName) . '-' . Str::random(8) . '.' . $extension;
        $relativePath = $storageDir . '/' . $finalFilename;
        $absolutePath = Storage::disk('local')->path($relativePath);

        // 4. Unduh berkas menggunakan sink
        try {
            $response = Http::timeout(self::TIMEOUT_SECONDS)
                ->withOptions(['sink' => $absolutePath])
                ->get($url);

            if (! $response->successful()) {
                if (file_exists($absolutePath)) {
                    @unlink($absolutePath);
                }
                throw new Exception("Gagal mengunduh berkas (HTTP {$response->status()}).");
            }
        } catch (Exception $e) {
            if (file_exists($absolutePath)) {
                @unlink($absolutePath);
            }
            throw new Exception('Gagal saat streaming berkas: ' . $e->getMessage());
        }

        $realSize = file_exists($absolutePath) ? (int) filesize($absolutePath) : 0;

        // Pastikan ukuran riil tidak melebihi batas maksimum
        if ($realSize > self::MAX_FILE_SIZE) {
            @unlink($absolutePath);
            throw new Exception('Ukuran berkas melebihi batas maksimum 500 MB saat diunduh.');
        }

        return [
            'filename' => $finalFilename,
            'file_path' => $relativePath,
            'file_size' => $realSize,
            'media_type' => $info['content_type'],
        ];
    }

    /**
     * Membersihkan dan menghasilkan nama file yang aman dari path traversal.
     */
    public function extractSafeFilename(string $url, string $contentType = ''): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $rawFilename = basename($path);

        // Sanitasi path traversal dan karakter berbahaya
        $rawFilename = preg_replace('/[^a-zA-Z0-9\._-]/', '', $rawFilename);

        if (empty($rawFilename) || ! str_contains($rawFilename, '.')) {
            $ext = 'mp4';
            if (str_contains($contentType, 'image/jpeg') || str_contains($contentType, 'image/jpg')) {
                $ext = 'jpg';
            } elseif (str_contains($contentType, 'image/png')) {
                $ext = 'png';
            } elseif (str_contains($contentType, 'audio/')) {
                $ext = 'mp3';
            }
            $rawFilename = 'media-' . date('Ymd-His') . '.' . $ext;
        }

        return $rawFilename;
    }

    /**
     * Memeriksa apakah URL memiliki ekstensi media yang umum.
     */
    protected function hasMediaExtension(string $url): bool
    {
        $path = strtolower(parse_url($url, PHP_URL_PATH) ?? '');
        $mediaExtensions = ['.mp4', '.mkv', '.webm', '.mov', '.avi', '.jpg', '.jpeg', '.png', '.webp', '.gif', '.mp3'];

        foreach ($mediaExtensions as $ext) {
            if (str_ends_with($path, $ext)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Format byte ke satuan yang mudah dibaca.
     */
    protected function formatBytes(?int $bytes): string
    {
        if (! $bytes) {
            return 'Ukuran tidak diketahui';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
