<?php

namespace Tests\Unit;

use App\Enums\DownloadRoute;
use App\Services\RouteDetectorService;
use Tests\TestCase;

class RouteDetectorServiceTest extends TestCase
{
    protected RouteDetectorService $detector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->detector = new RouteDetectorService();
    }

    /**
     * Uji bahwa URL YouTube sah terdeteksi sebagai Jalur B (Platform).
     */
    public function test_detects_youtube_as_platform_route(): void
    {
        $this->assertSame(DownloadRoute::Platform, $this->detector->detect('https://www.youtube.com/watch?v=dQw4w9WgXcQ'));
        $this->assertSame(DownloadRoute::Platform, $this->detector->detect('https://youtu.be/dQw4w9WgXcQ'));
        $this->assertSame(DownloadRoute::Platform, $this->detector->detect('https://m.youtube.com/watch?v=dQw4w9WgXcQ'));
        $this->assertSame('YouTube', $this->detector->getPlatformName('https://youtu.be/dQw4w9WgXcQ'));
    }

    /**
     * Uji bahwa URL TikTok sah terdeteksi sebagai Jalur B (Platform).
     */
    public function test_detects_tiktok_as_platform_route(): void
    {
        $this->assertSame(DownloadRoute::Platform, $this->detector->detect('https://www.tiktok.com/@user/video/12345'));
        $this->assertSame(DownloadRoute::Platform, $this->detector->detect('https://vt.tiktok.com/ZSxyz123/'));
        $this->assertSame('TikTok', $this->detector->getPlatformName('https://www.tiktok.com/@user/video/12345'));
    }

    /**
     * Uji bahwa URL Instagram sah terdeteksi sebagai Jalur B (Platform).
     */
    public function test_detects_instagram_as_platform_route(): void
    {
        $this->assertSame(DownloadRoute::Platform, $this->detector->detect('https://www.instagram.com/reel/C8xyz123/'));
        $this->assertSame('Instagram', $this->detector->getPlatformName('https://www.instagram.com/p/C8xyz123/'));
    }

    /**
     * Uji bahwa domain tipuan (phishing / domain mirip) TIDAK lolos ke Jalur B melainkan Jalur A.
     */
    public function test_rejects_phishing_domains_from_platform_route(): void
    {
        // youtube.evil.com BUKAN youtube asli
        $this->assertSame(DownloadRoute::Direct, $this->detector->detect('https://youtube.evil.com/video.mp4'));
        $this->assertSame(DownloadRoute::Direct, $this->detector->detect('https://not-youtube.com/watch?v=123'));
        $this->assertSame(DownloadRoute::Direct, $this->detector->detect('https://my-tiktok.com/video'));
    }

    /**
     * Uji bahwa berkas langsung publik terdeteksi sebagai Jalur A (Direct).
     */
    public function test_detects_direct_files_as_direct_route(): void
    {
        $this->assertSame(DownloadRoute::Direct, $this->detector->detect('https://example.com/files/sample-video.mp4'));
        $this->assertSame(DownloadRoute::Direct, $this->detector->detect('https://cdn.pixabay.com/photo/wallpaper.jpg'));
        $this->assertNull($this->detector->getPlatformName('https://example.com/sample.mp4'));
    }
}
