<div {{ $attributes->merge(['class' => 'rounded-xl border border-blue-900/50 bg-blue-950/30 p-4 text-sm text-blue-200 flex items-start gap-3']) }}>
    <x-lucide-info class="w-5 h-5 text-blue-400 shrink-0 mt-0.5" />
    <div class="space-y-1">
        <h4 class="font-semibold text-blue-300">{{ $title ?? 'Catatan Batasan Penggunaan Lokal' }}</h4>
        <p class="text-xs text-blue-200/80 leading-relaxed">
            {{ $slot->isEmpty() ? 'Aplikasi ini berjalan lokal di komputer Anda. Gunakan hanya untuk konten milik sendiri atau yang berlisensi bebas. Aplikasi ini tidak mendukung konten privat, DRM, atau yang memerlukan login.' : $slot }}
        </p>
    </div>
</div>
