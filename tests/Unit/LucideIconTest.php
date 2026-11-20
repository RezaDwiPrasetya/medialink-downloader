<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class LucideIconTest extends TestCase
{
    /**
     * Uji apakah Blade mampu merender komponen Lucide icon menjadi SVG yang valid.
     */
    public function test_lucide_icon_renders_as_svg(): void
    {
        $rendered = Blade::render('<x-lucide-download class="w-5 h-5 text-blue-500" />');

        $this->assertStringContainsString('<svg', $rendered);
        $this->assertStringContainsString('</svg>', $rendered);
        $this->assertStringContainsString('w-5 h-5 text-blue-500', $rendered);
    }

    /**
     * Uji apakah komponen icon lain (misal check-circle) juga berhasil dirender.
     */
    public function test_check_circle_icon_renders(): void
    {
        $rendered = Blade::render('<x-lucide-check-circle class="text-green-500" />');

        $this->assertStringContainsString('<svg', $rendered);
        $this->assertStringContainsString('text-green-500', $rendered);
    }
}
