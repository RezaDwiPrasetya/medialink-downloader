<?php

namespace App\Services;

use App\Enums\DownloadRoute;

class RouteDetectorService
{
    /**
     * Daftar host yang sah untuk Jalur B (Platform).
     * Pencocokan dilakukan persis (exact match), bukan substring, agar aman dari phishing.
     *
     * @var list<string>
     */
    protected array $platformHosts = [
        // YouTube
        'youtube.com',
        'www.youtube.com',
        'm.youtube.com',
        'music.youtube.com',
        'youtu.be',

        // TikTok
        'tiktok.com',
        'www.tiktok.com',
        'm.tiktok.com',
        'vm.tiktok.com',
        'vt.tiktok.com',

        // Instagram
        'instagram.com',
        'www.instagram.com',
        'm.instagram.com',
    ];

    /**
     * Mendeteksi apakah URL masuk ke Jalur A (Berkas Langsung) atau Jalur B (Platform).
     */
    public function detect(string $url): DownloadRoute
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');

        if (in_array($host, $this->platformHosts, true)) {
            return DownloadRoute::Platform;
        }

        return DownloadRoute::Direct;
    }

    /**
     * Mengambil nama label platform untuk tampilan informasi media.
     */
    public function getPlatformName(string $url): ?string
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');

        if (str_contains($host, 'youtu')) {
            return 'YouTube';
        }

        if (str_contains($host, 'tiktok')) {
            return 'TikTok';
        }

        if (str_contains($host, 'instagram')) {
            return 'Instagram';
        }

        return null;
    }
}
