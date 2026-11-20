<?php

namespace Tests\Feature;

use App\Enums\DownloadRoute;
use App\Enums\DownloadStatus;
use App\Jobs\DownloadMediaJob;
use App\Models\Download;
use App\Services\DirectDownloadService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DownloadQueueFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Uji bahwa endpoint POST /download membuat record pending dan men-dispatch job ke Queue.
     */
    public function test_download_request_creates_pending_record_and_dispatches_job(): void
    {
        Queue::fake();

        $response = $this->post('/download', [
            'url' => 'https://example.com/video.mp4',
            'route' => 'A',
        ]);

        $response->assertRedirect('/history');
        $response->assertSessionHas('info');

        $this->assertDatabaseHas('downloads', [
            'source_url' => 'https://example.com/video.mp4',
            'route' => 'A',
            'status' => 'pending',
        ]);

        Queue::assertPushed(DownloadMediaJob::class, function ($job) {
            return $job->download->source_url === 'https://example.com/video.mp4';
        });
    }

    /**
     * Uji bahwa DownloadMediaJob berhasil mengunduh berkas Jalur A dan mengubah status menjadi success.
     */
    public function test_download_media_job_executes_direct_download_successfully(): void
    {
        Storage::fake('local');

        Http::fake([
            'https://example.com/sample.mp4' => Http::sequence()
                ->push('', 200, ['Content-Type' => 'video/mp4', 'Content-Length' => 500])
                ->push('fake-mp4-stream-data', 200, ['Content-Type' => 'video/mp4']),
        ]);

        $download = Download::create([
            'source_url' => 'https://example.com/sample.mp4',
            'route' => DownloadRoute::Direct,
            'status' => DownloadStatus::Pending,
        ]);

        $job = new DownloadMediaJob($download);
        $job->handle(app(DirectDownloadService::class));

        $download->refresh();

        $this->assertSame(DownloadStatus::Success, $download->status);
        $this->assertNotNull($download->filename);
        $this->assertNotNull($download->file_path);
        $this->assertNotNull($download->downloaded_at);
        $this->assertNull($download->error_message);

        Storage::disk('local')->assertExists($download->file_path);
    }

    /**
     * Uji bahwa DownloadMediaJob menangani kegagalan unduhan dengan mengubah status menjadi failed dan mencatat error.
     */
    public function test_download_media_job_handles_failure_and_updates_status(): void
    {
        Storage::fake('local');

        Http::fake([
            'https://example.com/broken.mp4' => Http::response('Server Error', 500),
        ]);

        $download = Download::create([
            'source_url' => 'https://example.com/broken.mp4',
            'route' => DownloadRoute::Direct,
            'status' => DownloadStatus::Pending,
        ]);

        $job = new DownloadMediaJob($download);

        try {
            $job->handle(app(DirectDownloadService::class));
        } catch (Exception $e) {
            // Dikecualikan karena job sengaja melempar exception untuk mekanisme antrean
        }

        $download->refresh();

        $this->assertSame(DownloadStatus::Failed, $download->status);
        $this->assertNotNull($download->error_message);
        $this->assertStringContainsString('500', $download->error_message);
    }
}
