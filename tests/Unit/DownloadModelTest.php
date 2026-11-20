<?php

namespace Tests\Unit;

use App\Enums\DownloadRoute;
use App\Enums\DownloadStatus;
use App\Models\Download;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DownloadModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Uji apakah Factory berhasil membuat record unduhan yang valid di database.
     */
    public function test_factory_creates_valid_download_record(): void
    {
        $download = Download::factory()->create();

        $this->assertDatabaseHas('downloads', [
            'id' => $download->id,
            'source_url' => $download->source_url,
        ]);
    }

    /**
     * Uji apakah kolom route dan status otomatis di-cast menjadi PHP Enum.
     */
    public function test_casts_route_and_status_to_enums(): void
    {
        $download = Download::factory()->create([
            'route' => DownloadRoute::Direct,
            'status' => DownloadStatus::Pending,
        ]);

        $this->assertInstanceOf(DownloadRoute::class, $download->route);
        $this->assertInstanceOf(DownloadStatus::class, $download->status);
        $this->assertSame(DownloadRoute::Direct, $download->route);
        $this->assertSame(DownloadStatus::Pending, $download->status);
    }

    /**
     * Uji helper label dan warna badge pada DownloadStatus enum.
     */
    public function test_download_status_labels_and_badge_colors(): void
    {
        $this->assertSame('Menunggu', DownloadStatus::Pending->label());
        $this->assertSame('Sukses', DownloadStatus::Success->label());
        $this->assertSame('Gagal', DownloadStatus::Failed->label());

        $this->assertStringContainsString('yellow', DownloadStatus::Pending->badgeColor());
        $this->assertStringContainsString('green', DownloadStatus::Success->badgeColor());
        $this->assertStringContainsString('red', DownloadStatus::Failed->badgeColor());
    }

    /**
     * Uji helper label pada DownloadRoute enum.
     */
    public function test_download_route_labels(): void
    {
        $this->assertStringContainsString('Jalur A', DownloadRoute::Direct->label());
        $this->assertStringContainsString('Jalur B', DownloadRoute::Platform->label());
    }

    /**
     * Uji apakah accessor formatted_file_size memformat byte ke B/KB/MB dengan tepat.
     */
    public function test_formatted_file_size_accessor(): void
    {
        $downloadWithoutSize = new Download(['file_size' => null]);
        $this->assertSame('-', $downloadWithoutSize->formatted_file_size);

        $downloadBytes = new Download(['file_size' => 500]);
        $this->assertSame('500 B', $downloadBytes->formatted_file_size);

        $downloadKb = new Download(['file_size' => 2048]);
        $this->assertSame('2 KB', $downloadKb->formatted_file_size);

        $downloadMb = new Download(['file_size' => 52428800]); // 50 * 1024 * 1024
        $this->assertSame('50 MB', $downloadMb->formatted_file_size);
    }
}
