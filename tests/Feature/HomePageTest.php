<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    /**
     * Uji apakah halaman utama berhasil dibuka dan merender elemen form URL.
     */
    public function test_home_page_can_be_rendered(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('MediaLink');
        $response->assertSee('Downloader');
        $response->assertSee('Periksa URL');
        $response->assertSee('name="url"', false);
    }

    /**
     * Uji apakah pesan error dari session flash ditampilkan dengan benar.
     */
    public function test_home_page_displays_flash_error(): void
    {
        $response = $this->withSession(['error' => 'URL mengarah ke jaringan lokal yang dilarang.'])->get('/');

        $response->assertStatus(200);
        $response->assertSee('Pemeriksaan Gagal');
        $response->assertSee('URL mengarah ke jaringan lokal yang dilarang.');
    }

    /**
     * Uji apakah pesan sukses dari session flash ditampilkan dengan benar.
     */
    public function test_home_page_displays_flash_success(): void
    {
        $response = $this->withSession(['success' => 'Berkas berhasil disimpan.'])->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sukses');
        $response->assertSee('Berkas berhasil disimpan.');
    }

    /**
     * Uji apakah card preview media muncul jika data session preview tersedia.
     */
    public function test_home_page_displays_media_preview_when_session_present(): void
    {
        $response = $this->withSession([
            'preview' => [
                'title' => 'Tutorial Laravel 12',
                'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'route' => 'B',
                'duration' => '10:35',
                'type' => 'video/mp4',
            ]
        ])->get('/');

        $response->assertStatus(200);
        $response->assertSee('Media Terdeteksi');
        $response->assertSee('Tutorial Laravel 12');
        $response->assertSee('Jalur B (Platform)');
        $response->assertSee('Unduh Media Sekarang');
    }
}
