@extends('layouts.app')

@section('title', 'Riwayat Unduhan')

@section('content')
<div class="space-y-6">

    <!-- Header Halaman Riwayat -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-800">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2.5">
                <x-lucide-history class="w-6 h-6 text-blue-500" />
                <span>Riwayat Unduhan Media</span>
            </h1>
            <p class="text-sm text-slate-400 mt-1">
                Daftar semua media yang telah diproses di komputer ini.
            </p>
        </div>
        <div>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 transition shadow-sm">
                <x-lucide-plus class="w-4 h-4 text-blue-400" />
                <span>Unduh Media Baru</span>
            </a>
        </div>
    </div>

    <!-- Flash Alert: Notifikasi Status -->
    @if (session('error'))
        <x-alert type="error" title="Terjadi Kesalahan">
            {{ session('error') }}
        </x-alert>
    @endif

    @if (session('success'))
        <x-alert type="success" title="Berhasil">
            {{ session('success') }}
        </x-alert>
    @endif

    @if (session('info'))
        <x-alert type="info" title="Masuk Antrean">
            {{ session('info') }}
        </x-alert>
    @endif

    <!-- Riwayat Kosong (Empty State - Layar 7) -->
    @if ($downloads->isEmpty())
        <x-card class="py-12 text-center border-dashed border-slate-800 bg-slate-900/40">
            <div class="max-w-md mx-auto space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-slate-800/80 border border-slate-700 flex items-center justify-center text-slate-500 mx-auto shadow-inner">
                    <x-lucide-inbox class="w-8 h-8 text-slate-400" />
                </div>
                <div>
                    <h3 class="text-base font-semibold text-slate-200">Belum Ada Riwayat Unduhan</h3>
                    <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">
                        Media yang Anda unduh akan tercatat otomatis di sini, lengkap dengan tautan asli, ukuran berkas, dan status pemrosesan.
                    </p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-600/20 transition">
                        <x-lucide-plus class="w-4 h-4" />
                        <span>Mulai Unduh Media Pertama</span>
                    </a>
                </div>
            </div>
        </x-card>

    <!-- Tabel Riwayat Unduhan (Layar 6) -->
    @else
        <div class="rounded-xl border border-slate-800 bg-slate-900/70 shadow-xl backdrop-blur-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-800 bg-slate-950/60 text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
                            <th scope="col" class="py-3 px-3.5 whitespace-nowrap">Waktu</th>
                            <th scope="col" class="py-3 px-3.5">Sumber URL</th>
                            <th scope="col" class="py-3 px-3.5">Nama Berkas</th>
                            <th scope="col" class="py-3 px-3.5 whitespace-nowrap">Ukuran</th>
                            <th scope="col" class="py-3 px-3.5 whitespace-nowrap">Jalur</th>
                            <th scope="col" class="py-3 px-3.5 whitespace-nowrap">Status</th>
                            <th scope="col" class="py-3 px-3.5 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/70">
                        @foreach ($downloads as $item)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-3 px-3.5 text-slate-400 whitespace-nowrap font-mono text-[11px]">
                                    {{ $item->created_at ? $item->created_at->timezone('Asia/Jakarta')->format('d/m H:i') . ' WIB' : '-' }}
                                </td>
                                <td class="py-3 px-3.5">
                                    <a href="{{ $item->source_url }}" target="_blank" rel="noopener noreferrer" class="text-blue-400 hover:text-blue-300 hover:underline max-w-[140px] sm:max-w-[200px] truncate block font-mono text-[11px]" title="{{ $item->source_url }}">
                                        {{ $item->source_url }}
                                    </a>
                                </td>
                                <td class="py-3 px-3.5">
                                    @if ($item->filename)
                                        <span class="font-medium text-slate-200 block truncate max-w-[150px]" title="{{ $item->filename }}">
                                            {{ $item->filename }}
                                        </span>
                                        @if ($item->media_type)
                                            <span class="text-[10px] text-slate-500 uppercase">{{ $item->media_type }}</span>
                                        @endif
                                    @else
                                        <span class="text-slate-500 italic">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3.5 text-slate-300 whitespace-nowrap font-mono text-[11px]">
                                    {{ $item->formatted_file_size }}
                                </td>
                                <td class="py-3 px-3.5 whitespace-nowrap">
                                    <x-badge variant="default">
                                        {{ $item->route->value === 'A' ? 'Jalur A' : 'Jalur B' }}
                                    </x-badge>
                                </td>
                                <td class="py-3 px-3.5 whitespace-nowrap">
                                    <x-badge :variant="$item->status->value">
                                        {{ $item->status->label() }}
                                    </x-badge>
                                    @if ($item->status === \App\Enums\DownloadStatus::Failed && $item->error_message)
                                        <p class="text-[10px] text-red-400 mt-1 max-w-[150px] truncate" title="{{ $item->error_message }}">
                                            {{ $item->error_message }}
                                        </p>
                                    @endif
                                </td>
                                <td class="py-3 px-3.5 text-right whitespace-nowrap">
                                    @if ($item->status === \App\Enums\DownloadStatus::Success && $item->file_path)
                                        <a href="{{ route('downloads.serve', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-600 hover:bg-blue-500 text-white shadow-sm transition">
                                            <x-lucide-download class="w-3.5 h-3.5" />
                                            <span>Unduh</span>
                                        </a>
                                    @elseif ($item->status === \App\Enums\DownloadStatus::Pending)
                                        <span class="inline-flex items-center gap-1.5 text-amber-400 text-xs">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                            <span>Memproses</span>
                                        </span>
                                    @else
                                        <span class="text-slate-600">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
                                    @if ($item->status === \App\Enums\DownloadStatus::Success && $item->file_path)
                                        <a href="{{ route('downloads.serve', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-600 hover:bg-blue-500 text-white shadow-sm transition">
                                            <x-lucide-download class="w-3.5 h-3.5" />
                                            <span>Unduh</span>
                                        </a>
                                    @elseif ($item->status === \App\Enums\DownloadStatus::Pending)
                                        <span class="inline-flex items-center gap-1.5 text-amber-400 text-xs">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                            <span>Memproses</span>
                                        </span>
                                    @else
                                        <span class="text-slate-600">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginasi -->
            @if ($downloads->hasPages())
                <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                    {{ $downloads->links() }}
                </div>
            @endif
        </div>
    @endif

</div>
@endsection
