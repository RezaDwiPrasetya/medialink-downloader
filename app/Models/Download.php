<?php

namespace App\Models;

use App\Enums\DownloadRoute;
use App\Enums\DownloadStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'source_url',
        'filename',
        'file_path',
        'file_size',
        'media_type',
        'route',
        'status',
        'error_message',
        'downloaded_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'route' => DownloadRoute::class,
            'status' => DownloadStatus::class,
            'downloaded_at' => 'datetime',
        ];
    }

    /**
     * Format ukuran berkas ke format yang mudah dibaca (KB / MB / GB).
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (! $this->file_size) {
            return '-';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->file_size;
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
