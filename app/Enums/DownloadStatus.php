<?php

namespace App\Enums;

enum DownloadStatus: string
{
    case Pending = 'pending';
    case Success = 'success';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::Success => 'Sukses',
            self::Failed => 'Gagal',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
            self::Success => 'bg-green-500/20 text-green-400 border-green-500/30',
            self::Failed => 'bg-red-500/20 text-red-400 border-red-500/30',
        };
    }
}
