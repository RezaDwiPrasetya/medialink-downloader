<?php

namespace Tests\Unit;

use App\Services\DirectDownloadService;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DirectDownloadServiceTest extends TestCase
{
    protected DirectDownloadService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DirectDownloadService();
    }

    /**
     * Uji sanitasi nama berkas dari path traversal dan karakter aneh.
     */
    public function test_extract_safe_filename(): void
    {
        $safe1 = $this->service->extractSafeFilename('https://example.com/files/sample-video.mp4');
        $this->assertSame('sample-video.mp4', $safe1);

        // Path traversal disanitasi
        $safe2 = $this->service->extractSafeFilename('https://example.com/../../etc/passwd.mp4');
        $this->assertStringNotContainsString('../', $safe2);
        $this->assertStringEndsWith('.mp4', $safe2);

        // URL tanpa nama file menghasilkan nama fallback
        $safe3 = $this->service->extractSafeFilename('https://example.com/', 'image/jpeg');
        $this->assertStringStartsWith('media-', $safe3);
        $this->assertStringEndsWith('.jpg', $safe3);
    }

    /**
     * Uji inspeksi header berkas media valid via HEAD request.
     */
    public function test_inspect_validates_media_content_type(): void
    {
        Http::fake([
            'https://example.com/video.mp4' => Http::response('', 200, [
                'Content-Type' => 'video/mp4',
                'Content-Length' => 1048576, // 1 MB
            ]),
        ]);

        $info = $this->service->inspect('https://example.com/video.mp4');

        $this->assertSame('video/mp4', $info['content_type']);
        $this->assertSame(1048576, $info['content_length']);
        $this->assertSame('1 MB', $info['formatted_size']);
        $this->assertSame('video.mp4', $info['filename']);
    }

    /**
     * Uji bahwa Content-Type text/html ditolak dengan exception.
     */
    public function test_inspect_rejects_html_or_non_media_type(): void
    {
        Http::fake([
            'https://example.com/webpage' => Http::response('<html></html>', 200, [
                'Content-Type' => 'text/html; charset=UTF-8',
            ]),
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('URL ini bukan berkas media yang didukung');

        $this->service->inspect('https://example.com/webpage');
    }

    /**
     * Uji bahwa ukuran file lebih dari 500 MB ditolak saat inspeksi.
     */
    public function test_inspect_rejects_file_exceeding_max_size(): void
    {
        Http::fake([
            'https://example.com/giant.mp4' => Http::response('', 200, [
                'Content-Type' => 'video/mp4',
                'Content-Length' => 600 * 1024 * 1024, // 600 MB
            ]),
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Ukuran berkas melebihi batas maksimum 500 MB');

        $this->service->inspect('https://example.com/giant.mp4');
    }

    /**
     * Uji pengunduhan berkas ke storage disk local privat.
     */
    public function test_download_stores_file_in_storage(): void
    {
        Storage::fake('local');

        Http::fake([
            'https://example.com/sample.mp4' => Http::sequence()
                ->push('', 200, ['Content-Type' => 'video/mp4', 'Content-Length' => 100])
                ->push('fake-binary-content-mp4', 200, ['Content-Type' => 'video/mp4']),
        ]);

        $result = $this->service->download('https://example.com/sample.mp4');

        $this->assertNotEmpty($result['filename']);
        $this->assertSame('video/mp4', $result['media_type']);
        Storage::disk('local')->assertExists($result['file_path']);
    }
}
