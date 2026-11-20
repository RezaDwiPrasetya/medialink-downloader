<?php

namespace Tests\Feature;

use App\Enums\DownloadRoute;
use App\Enums\DownloadStatus;
use App\Models\Download;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HistoryPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Uji bahwa halaman riwayat berhasil dibuka (HTTP 200).
     */
    public function test_history_page_renders_successfully(): void
    {
        $response = $this->get('/history');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Unduhan Media');
        $response->assertSee('Unduh Media Baru');
    }

    /**
     * Uji bahwa empty state tampil dengan benar jika belum ada data unduhan di database.
     */
    public function test_history_page_displays_empty_state_when_no_downloads(): void
    {
        $response = $this->get('/history');

        $response->assertStatus(200);
        $response->assertSee('Belum Ada Riwayat Unduhan');
        $response->assertSee('Mulai Unduh Media Pertama');
    }

    /**
     * Uji bahwa daftar unduhan tampil lengkap dengan status, ukuran, dan nama file.
     */
    public function test_history_page_displays_download_list_and_details(): void
    {
        $successDownload = Download::create([
            'source_url' => 'https://example.com/video-testing.mp4',
            'filename' => 'video-testing.mp4',
            'file_path' => 'downloads/video-testing.mp4',
            'file_size' => 15728640, // 15 MB
            'media_type' => 'video/mp4',
            'route' => DownloadRoute::Direct,
            'status' => DownloadStatus::Success,
            'downloaded_at' => now(),
        ]);

        $failedDownload = Download::create([
            'source_url' => 'https://example.com/broken-link.mp4',
            'route' => DownloadRoute::Direct,
            'status' => DownloadStatus::Failed,
            'error_message' => 'Koneksi timeout 404',
        ]);

        $response = $this->get('/history');

        $response->assertStatus(200);
        $response->assertDontSee('Belum Ada Riwayat Unduhan');
        $response->assertSee('video-testing.mp4');
        $response->assertSee('15 MB');
        $response->assertSee('Sukses');
        $response->assertSee('Gagal');
        $response->assertSee('WIB');
        $response->assertSee('Koneksi timeout 404');
        $response->assertSee(route('downloads.serve', $successDownload->id));
    }

    /**
     * Uji bahwa paginasi berfungsi jika jumlah rekaman unduhan melebihi 15 item.
     */
    public function test_history_page_paginates_when_records_exceed_limit(): void
    {
        Download::factory()->count(25)->create();

        $response = $this->get('/history');

        $response->assertStatus(200);
        $response->assertSee('Next &raquo;', false);
    }

    /**
     * Uji bahwa endpoint serve berhasil mengunduh berkas fisik yang tersimpan di disk.
     */
    public function test_serve_route_downloads_file_when_exists(): void
    {
        Storage::fake('local');
        $filePath = 'downloads/sample-test.mp4';
        Storage::disk('local')->put($filePath, 'fake-binary-content-mp4');

        $download = Download::create([
            'source_url' => 'https://example.com/sample-test.mp4',
            'filename' => 'sample-test.mp4',
            'file_path' => $filePath,
            'file_size' => 22,
            'media_type' => 'video/mp4',
            'route' => DownloadRoute::Direct,
            'status' => DownloadStatus::Success,
            'downloaded_at' => now(),
        ]);

        $response = $this->get(route('downloads.serve', $download->id));

        $response->assertStatus(200);
        $response->assertDownload('sample-test.mp4');
    }

    /**
     * Uji bahwa endpoint serve me-redirect back dengan flash error jika berkas fisik tidak ada di disk.
     */
    public function test_serve_route_redirects_back_when_file_not_found(): void
    {
        Storage::fake('local');

        $download = Download::create([
            'source_url' => 'https://example.com/missing.mp4',
            'filename' => 'missing.mp4',
            'file_path' => 'downloads/missing.mp4',
            'file_size' => 100,
            'media_type' => 'video/mp4',
            'route' => DownloadRoute::Direct,
            'status' => DownloadStatus::Success,
        ]);

        $response = $this->get(route('downloads.serve', $download->id));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Berkas fisik tidak ditemukan di sistem penyimpanan server lokal.');
    }

    /**
     * Uji bahwa endpoint serve mengembalikan 404 jika ID rekaman tidak ada di database.
     */
    public function test_serve_route_returns_404_for_non_existent_id(): void
    {
        $response = $this->get('/downloads/999999/serve');

        $response->assertStatus(404);
    }
}
