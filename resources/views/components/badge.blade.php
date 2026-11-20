@props([
    'variant' => 'default', // 'pending', 'success', 'failed', 'default'
])

@php
    $variants = [
        'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
        'success' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
        'failed' => 'bg-red-500/10 text-red-400 border-red-500/20',
        'default' => 'bg-slate-800 text-slate-300 border-slate-700',
    ];

    $classes = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ' . ($variants[$variant] ?? $variants['default']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
