<?php

namespace App\Jobs;

use App\Enums\DownloadRoute;
use App\Enums\DownloadStatus;
use App\Models\Download;
use App\Services\DirectDownloadService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class DownloadMediaJob implements ShouldQueue
{
    use Queueable;

    /**
     * Jumlah percobaan eksekusi jika gagal.
     */
    public int $tries = 1;

    /**
     * Waktu timeout proses dalam detik (5 menit).
     */
    public int $timeout = 300;

    /**
     * Buat instance job unduhan baru.
     */
    public function __construct(
        public Download $download
    ) {}

    /**
     * Eksekusi proses unduhan di latar belakang.
     */
    public function handle(DirectDownloadService $directService): void
    {
        try {
            if ($this->download->route === DownloadRoute::Direct) {
                // Eksekusi pengunduhan Jalur A (Berkas Langsung)
                $result = $directService->download($this->download->source_url);

                $this->download->update([
                    'filename' => $result['filename'],
                    'file_path' => $result['file_path'],
                    'file_size' => $result['file_size'],
                    'media_type' => $result['media_type'],
                    'status' => DownloadStatus::Success,
                    'error_message' => null,
                    'downloaded_at' => now(),
                ]);
            } else {
                // Jalur B (Platform via yt-dlp) - siap dieksekusi di Sprint 4
                throw new Exception('Layanan unduhan platform (Jalur B) sedang dalam tahap integrasi Sprint 4.');
            }
        } catch (Exception $e) {
            Log::error('DownloadMediaJob gagal untuk URL: ' . $this->download->source_url . ' - ' . $e->getMessage());

            $this->download->update([
                'status' => DownloadStatus::Failed,
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
