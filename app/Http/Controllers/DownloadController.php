<?php

namespace App\Http\Controllers;

use App\Enums\DownloadRoute;
use App\Enums\DownloadStatus;
use App\Http\Requests\DownloadRequest;
use App\Jobs\DownloadMediaJob;
use App\Models\Download;
use App\Services\DirectDownloadService;
use App\Services\RouteDetectorService;
use App\Services\UrlValidatorService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DownloadController extends Controller
{
    /**
     * Menampilkan halaman utama form input URL.
     */
    public function index(): View
    {
        return view('home');
    }

    /**
     * Memeriksa keamanan, mendeteksi jalur, dan mengambil metadata media.
     */
    public function check(
        DownloadRequest $request,
        UrlValidatorService $validator,
        RouteDetectorService $detector,
        DirectDownloadService $directService
    ): RedirectResponse {
        $url = $request->validated('url');

        // 1. Jalankan SSRF Guard
        if (! $validator->isSafe($url)) {
            return back()
                ->withInput()
                ->with('error', $validator->getLastError() ?? 'URL mengarah ke alamat yang tidak diizinkan.');
        }

        // 2. Deteksi Jalur A (Berkas Langsung) atau Jalur B (Platform)
        $route = $detector->detect($url);

        // 3. Pemeriksaan Khusus Jalur A (Berkas Langsung)
        if ($route === DownloadRoute::Direct) {
            try {
                $info = $directService->inspect($url);
            } catch (Exception $e) {
                return back()
                    ->withInput()
                    ->with('error', $e->getMessage());
            }

            return back()
                ->withInput()
                ->with('preview', [
                    'title' => $info['filename'],
                    'url' => $url,
                    'route' => 'A',
                    'size' => $info['formatted_size'],
                    'type' => $info['content_type'],
                ])
                ->with('success', "Berkas langsung terverifikasi ({$info['formatted_size']}) dan siap diunduh!");
        }

        // 4. Pemeriksaan Khusus Jalur B (Platform: YouTube, TikTok, Instagram)
        $platform = $detector->getPlatformName($url) ?? 'Platform Media';

        return back()
            ->withInput()
            ->with('preview', [
                'title' => "Video {$platform}",
                'url' => $url,
                'route' => 'B',
                'type' => 'Video Platform (' . $platform . ')',
                'duration' => 'Siap diunduh via yt-dlp',
            ])
            ->with('success', "Tautan {$platform} valid dan siap diunduh!");
    }

    /**
     * Menerima instruksi unduhan, membuat record di database, dan mengirim job ke antrean Queue.
     */
    public function download(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'url' => ['required', 'string', 'url'],
            'route' => ['required', 'string', 'in:A,B'],
        ]);

        $download = Download::create([
            'source_url' => $validated['url'],
            'route' => DownloadRoute::from($validated['route']),
            'status' => DownloadStatus::Pending,
        ]);

        DownloadMediaJob::dispatch($download);

        return redirect('/history')
            ->with('info', "Unduhan untuk '{$download->source_url}' telah dimasukkan ke dalam antrean (ID: #{$download->id}).");
    }
}
