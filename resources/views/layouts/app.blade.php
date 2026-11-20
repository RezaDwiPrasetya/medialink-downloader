<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' . config('app.name', 'MediaLink Downloader') : config('app.name', 'MediaLink Downloader') }}</title>

    <!-- Scripts and Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased selection:bg-blue-600 selection:text-white">

    <!-- Header / Navbar -->
    <header class="border-b border-slate-800 bg-slate-900/60 backdrop-blur sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 font-bold text-lg tracking-tight hover:opacity-90 transition">
                <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                    <x-lucide-download class="w-5 h-5" />
                </div>
                <span>MediaLink <span class="text-blue-500 font-semibold">Downloader</span></span>
            </a>

            <nav class="flex items-center gap-1 sm:gap-2">
                <a href="{{ url('/') }}" 
                   class="px-3.5 py-1.5 rounded-md text-sm font-medium transition {{ request()->is('/') ? 'bg-slate-800 text-blue-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                   Beranda
                </a>
                <a href="{{ url('/history') }}" 
                   class="px-3.5 py-1.5 rounded-md text-sm font-medium transition {{ request()->is('history*') ? 'bg-slate-800 text-blue-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                   Riwayat Unduhan
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 py-8 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 bg-slate-900/40 py-6 text-center text-xs text-slate-500">
        <div class="max-w-5xl mx-auto px-4">
            <p>MediaLink Downloader &bull; Localhost Environment (Flock) &bull; Khusus Penggunaan Edukasi Pribadi</p>
        </div>
    </footer>

</body>
</html>
