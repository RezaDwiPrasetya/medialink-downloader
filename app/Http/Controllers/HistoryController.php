<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HistoryController extends Controller
{
    /**
     * Menampilkan daftar riwayat unduhan dengan paginasi.
     */
    public function index(): View
    {
        $downloads = Download::latest()->paginate(15);

        return view('history', compact('downloads'));
    }

    /**
     * Menyajikan berkas fisik yang tersimpan di storage privat untuk diunduh user.
     */
    public function serve(int $id): StreamedResponse|RedirectResponse
    {
        $download = Download::findOrFail($id);

        if (! $download->file_path || ! Storage::disk('local')->exists($download->file_path)) {
            return back()->with('error', 'Berkas fisik tidak ditemukan di sistem penyimpanan server lokal.');
        }

        return Storage::disk('local')->download(
            $download->file_path,
            $download->filename ?? basename($download->file_path)
        );
    }
}
