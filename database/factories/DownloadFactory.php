<?php

namespace Database\Factories;

use App\Enums\DownloadRoute;
use App\Enums\DownloadStatus;
use App\Models\Download;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Download>
 */
class DownloadFactory extends Factory
{
    protected $model = Download::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(DownloadStatus::cases());
        $route = fake()->randomElement(DownloadRoute::cases());
        $isSuccess = $status === DownloadStatus::Success;

        return [
            'source_url' => $route === DownloadRoute::Platform
                ? fake()->randomElement([
                    'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'https://www.tiktok.com/@user/video/1234567890',
                    'https://www.instagram.com/reel/C8xyz123/',
                ])
                : fake()->randomElement([
                    'https://example.com/media/sample-video.mp4',
                    'https://cdn.example.org/images/photo-nature.jpg',
                ]),
            'filename' => $isSuccess ? fake()->slug() . ($route === DownloadRoute::Platform ? '.mp4' : '.jpg') : null,
            'file_path' => $isSuccess ? 'downloads/' . fake()->uuid() . '.mp4' : null,
            'file_size' => $isSuccess ? fake()->numberBetween(1024 * 500, 1024 * 1024 * 80) : null,
            'media_type' => $isSuccess ? 'video/mp4' : null,
            'route' => $route,
            'status' => $status,
            'error_message' => $status === DownloadStatus::Failed ? 'Koneksi timeout saat mengunduh berkas.' : null,
            'downloaded_at' => $isSuccess ? fake()->dateTimeBetween('-7 days', 'now') : null,
        ];
    }
}
