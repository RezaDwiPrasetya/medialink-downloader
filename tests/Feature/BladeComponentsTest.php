<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class BladeComponentsTest extends TestCase
{
    /**
     * Uji komponen notice-box merender judul dan isi catatan batasan.
     */
    public function test_notice_box_component_renders(): void
    {
        $rendered = Blade::render('<x-notice-box />');

        $this->assertStringContainsString('Catatan Batasan Penggunaan Lokal', $rendered);
        $this->assertStringContainsString('berjalan lokal di komputer Anda', $rendered);
        $this->assertStringContainsString('<svg', $rendered);
    }

    /**
     * Uji komponen alert merender tipe error dan success dengan class warna yang sesuai.
     */
    public function test_alert_component_renders_different_types(): void
    {
        $errorAlert = Blade::render('<x-alert type="error" title="Gagal">Terjadi kesalahan URL</x-alert>');
        $this->assertStringContainsString('Gagal', $errorAlert);
        $this->assertStringContainsString('Terjadi kesalahan URL', $errorAlert);
        $this->assertStringContainsString('border-red-900', $errorAlert);

        $successAlert = Blade::render('<x-alert type="success" title="Sukses">File berhasil diunduh</x-alert>');
        $this->assertStringContainsString('Sukses', $successAlert);
        $this->assertStringContainsString('File berhasil diunduh', $successAlert);
        $this->assertStringContainsString('border-emerald-900', $successAlert);
    }

    /**
     * Uji komponen button merender tag button dengan variant primary dan atribut.
     */
    public function test_button_component_renders(): void
    {
        $rendered = Blade::render('<x-button variant="primary" type="submit">Kirim Data</x-button>');

        $this->assertStringContainsString('<button type="submit"', $rendered);
        $this->assertStringContainsString('bg-blue-600', $rendered);
        $this->assertStringContainsString('Kirim Data', $rendered);
    }

    /**
     * Uji komponen card merender container dan judul.
     */
    public function test_card_component_renders(): void
    {
        $rendered = Blade::render('<x-card title="Preview Media"><div>Konten Card</div></x-card>');

        $this->assertStringContainsString('Preview Media', $rendered);
        $this->assertStringContainsString('Konten Card', $rendered);
    }

    /**
     * Uji komponen badge merender status pill.
     */
    public function test_badge_component_renders(): void
    {
        $rendered = Blade::render('<x-badge variant="success">Selesai</x-badge>');

        $this->assertStringContainsString('text-emerald-400', $rendered);
        $this->assertStringContainsString('Selesai', $rendered);
    }
}
