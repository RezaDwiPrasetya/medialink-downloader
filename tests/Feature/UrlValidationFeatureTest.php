<?php

namespace Tests\Feature;

use Tests\TestCase;

class UrlValidationFeatureTest extends TestCase
{
    /**
     * Uji bahwa input kosong ditolak dengan pesan validasi.
     */
    public function test_empty_url_is_rejected(): void
    {
        $response = $this->post('/check', [
            'url' => '',
        ]);

        $response->assertSessionHasErrors(['url']);
    }

    /**
     * Uji bahwa teks bukan URL ditolak dengan pesan validasi.
     */
    public function test_invalid_url_format_is_rejected(): void
    {
        $response = $this->post('/check', [
            'url' => 'bukan-sebuah-url',
        ]);

        $response->assertSessionHasErrors(['url']);
    }

    /**
     * Uji bahwa URL localhost ditolak oleh SSRF Guard dan menghasilkan session error.
     */
    public function test_localhost_url_is_blocked_by_ssrf_guard(): void
    {
        $response = $this->post('/check', [
            'url' => 'http://localhost/secret',
        ]);

        $response->assertSessionHas('error');
        $response->assertSessionMissing('preview');
    }

    /**
     * Uji bahwa IP privat LAN ditolak oleh SSRF Guard.
     */
    public function test_private_lan_ip_is_blocked_by_ssrf_guard(): void
    {
        $response = $this->post('/check', [
            'url' => 'http://192.168.1.1/router-login',
        ]);

        $response->assertSessionHas('error');
        $response->assertSessionMissing('preview');
    }

    /**
     * Uji bahwa domain berkas publik yang sah lolos pemeriksaan dan memunculkan session preview.
     */
    public function test_valid_public_domain_passes_and_sets_preview(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'https://example.com/sample-video.mp4' => \Illuminate\Support\Facades\Http::response('', 200, [
                'Content-Type' => 'video/mp4',
                'Content-Length' => 15000000,
            ]),
        ]);

        $response = $this->post('/check', [
            'url' => 'https://example.com/sample-video.mp4',
        ]);

        $response->assertSessionHas('preview');
        $response->assertSessionHas('success');
    }

    /**
     * Uji bahwa link platform (YouTube) terdeteksi sebagai Jalur B.
     */
    public function test_platform_link_is_routed_to_route_b(): void
    {
        $response = $this->post('/check', [
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);

        $response->assertSessionHas('preview');
        $preview = session('preview');
        $this->assertSame('B', $preview['route']);
        $this->assertStringContainsString('YouTube', $preview['title']);
    }

    /**
     * Uji bahwa berkas non-media (misal halaman HTML) ditolak saat inspeksi Jalur A.
     */
    public function test_non_media_direct_url_is_rejected(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'https://example.com/not-media' => \Illuminate\Support\Facades\Http::response('<html></html>', 200, [
                'Content-Type' => 'text/html',
            ]),
        ]);

        $response = $this->post('/check', [
            'url' => 'https://example.com/not-media',
        ]);

        $response->assertSessionHas('error');
        $response->assertSessionMissing('preview');
    }
}
