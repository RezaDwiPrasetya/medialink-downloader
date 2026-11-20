<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class BaseLayoutTest extends TestCase
{
    /**
     * Uji apakah layout dasar Blade merender navbar, logo, dan footer dengan benar.
     */
    public function test_base_layout_renders_header_navigation_and_footer(): void
    {
        $view = Blade::render(
            '@extends("layouts.app") @section("content") <div id="test-content">Halo Dunia</div> @endsection'
        );

        $this->assertStringContainsString('MediaLink', $view);
        $this->assertStringContainsString('Downloader', $view);
        $this->assertStringContainsString('Beranda', $view);
        $this->assertStringContainsString('Riwayat Unduhan', $view);
        $this->assertStringContainsString('Halo Dunia', $view);
        $this->assertStringContainsString('Localhost Environment (Flock)', $view);
    }
}
