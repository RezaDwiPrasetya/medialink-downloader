<?php

namespace App\Enums;

enum DownloadRoute: string
{
    case Direct = 'A';
    case Platform = 'B';

    public function label(): string
    {
        return match ($this) {
            self::Direct => 'Berkas Langsung (Jalur A)',
            self::Platform => 'Platform / yt-dlp (Jalur B)',
        };
    }
}
