@props([
    'type' => 'info', // 'error', 'success', 'warning', 'info'
    'title' => null,
])

@php
    $styles = match ($type) {
        'error' => [
            'box' => 'border-red-900/60 bg-red-950/40 text-red-200',
            'title' => 'text-red-300',
            'icon' => 'lucide-alert-circle',
            'iconColor' => 'text-red-400',
        ],
        'success' => [
            'box' => 'border-emerald-900/60 bg-emerald-950/40 text-emerald-200',
            'title' => 'text-emerald-300',
            'icon' => 'lucide-check-circle',
            'iconColor' => 'text-emerald-400',
        ],
        'warning' => [
            'box' => 'border-amber-900/60 bg-amber-950/40 text-amber-200',
            'title' => 'text-amber-300',
            'icon' => 'lucide-alert-triangle',
            'iconColor' => 'text-amber-400',
        ],
        default => [
            'box' => 'border-blue-900/60 bg-blue-950/40 text-blue-200',
            'title' => 'text-blue-300',
            'icon' => 'lucide-info',
            'iconColor' => 'text-blue-400',
        ],
    };
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl border p-4 text-sm flex items-start gap-3 shadow-lg ' . $styles['box']]) }} role="alert">
    @if ($styles['icon'] === 'lucide-alert-circle')
        <x-lucide-alert-circle class="w-5 h-5 shrink-0 mt-0.5 {{ $styles['iconColor'] }}" />
    @elseif ($styles['icon'] === 'lucide-check-circle')
        <x-lucide-check-circle class="w-5 h-5 shrink-0 mt-0.5 {{ $styles['iconColor'] }}" />
    @elseif ($styles['icon'] === 'lucide-alert-triangle')
        <x-lucide-alert-triangle class="w-5 h-5 shrink-0 mt-0.5 {{ $styles['iconColor'] }}" />
    @else
        <x-lucide-info class="w-5 h-5 shrink-0 mt-0.5 {{ $styles['iconColor'] }}" />
    @endif

    <div class="space-y-0.5 flex-1 text-sm">
        @if ($title)
            <div class="font-semibold {{ $styles['title'] }}">{{ $title }}</div>
        @endif
        <div class="leading-relaxed">{{ $slot }}</div>
    </div>
</div>
