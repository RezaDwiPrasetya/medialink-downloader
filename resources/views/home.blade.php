@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Hero Header -->
    <div class="text-center space-y-2 py-4">
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white">
            MediaLink <span class="text-blue-500">Downloader</span>
        </h1>
        <p class="text-sm sm:text-base text-slate-400 max-w-lg mx-auto">
            Unduh video & foto ke komputer lokal secara aman, bebas iklan, dan terpercaya.
        </p>
    </div>

    <!-- Notice Box Disclaimer -->
    <x-notice-box />

    <!-- Flash Messages (Success / Error) -->
    @if (session('success'))
        <x-alert type="success" title="Sukses">
            {{ session('success') }}
        </x-alert>
    @endif

    @if (session('error'))
        <x-alert type="error" title="Pemeriksaan Gagal">
            {{ session('error') }}
        </x-alert>
    @endif

    @if ($errors->any())
        <x-alert type="error" title="Input Tidak Valid">
            <ul class="list-disc list-inside space-y-0.5 text-xs sm:text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <!-- Form Input URL Card -->
    <x-card>
        <form action="{{ url('/check') }}" method="POST" class="space-y-4" id="check-form">
            @csrf

            <div class="space-y-1.5">
                <label for="url" class="block text-sm font-medium text-slate-200">
                    URL Media
                </label>
                <div class="relative rounded-lg shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                        <x-lucide-link class="w-4 h-4" />
                    </div>
                    <input 
                        type="text" 
                        name="url" 
                        id="url" 
                        value="{{ old('url', session('preview.url')) }}"
                        placeholder="https://www.youtube.com/watch?v=... atau https://contoh.com/video.mp4" 
                        required
                        class="block w-full rounded-lg border border-slate-700 bg-slate-950/70 pl-10 pr-4 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition"
                    >
                </div>
                <p class="text-xs text-slate-400">
                    Mendukung berkas langsung (MP4, JPG, PNG) dan link platform (YouTube, TikTok, Instagram).
                </p>
            </div>

            <div class="flex justify-end pt-2">
                <x-button type="submit" variant="primary" class="w-full sm:w-auto">
                    <x-lucide-search class="w-4 h-4" />
                    <span>Periksa URL</span>
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Media Preview State (Jika URL lolos pemeriksaan) -->
    @if (session('preview'))
        @php
            $preview = session('preview');
        @endphp
        <x-card title="Media Terdeteksi" class="border-blue-800/60 bg-blue-950/20">
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row gap-4 items-start">
                    @if (!empty($preview['thumbnail']))
                        <img 
                            src="{{ $preview['thumbnail'] }}" 
                            alt="Thumbnail" 
                            class="w-full sm:w-44 h-28 object-cover rounded-lg border border-slate-800 shadow"
                        >
                    @else
                        <div class="w-full sm:w-44 h-28 bg-slate-800 rounded-lg flex items-center justify-center text-slate-500 border border-slate-700">
                            <x-lucide-film class="w-8 h-8" />
                        </div>
                    @endif

                    <div class="space-y-2 flex-1 text-sm">
                        <h4 class="font-bold text-slate-100 text-base leading-snug">
                            {{ $preview['title'] ?? 'Berkas Media Langsung' }}
                        </h4>
                        
                        <div class="grid grid-cols-2 gap-2 text-xs text-slate-300">
                            <div>
                                <span class="text-slate-500">Jalur:</span> 
                                <x-badge variant="default">
                                    {{ $preview['route'] === 'B' ? 'Jalur B (Platform)' : 'Jalur A (Berkas Langsung)' }}
                                </x-badge>
                            </div>
                            @if (!empty($preview['duration']))
                                <div><span class="text-slate-500">Durasi:</span> {{ $preview['duration'] }}</div>
                            @endif
                            @if (!empty($preview['size']))
                                <div><span class="text-slate-500">Ukuran:</span> {{ $preview['size'] }}</div>
                            @endif
                            @if (!empty($preview['type']))
                                <div><span class="text-slate-500">Format:</span> {{ $preview['type'] }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Form Trigger Download -->
                <form action="{{ url('/download') }}" method="POST" class="pt-2 border-t border-slate-800/80 flex justify-end">
                    @csrf
                    <input type="hidden" name="url" value="{{ $preview['url'] }}">
                    <input type="hidden" name="route" value="{{ $preview['route'] }}">
                    <x-button type="submit" variant="primary" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/20 focus:ring-emerald-500">
                        <x-lucide-download class="w-4 h-4" />
                        <span>Unduh Media Sekarang</span>
                    </x-button>
                </form>
            </div>
        </x-card>
    @endif

</div>
@endsection
