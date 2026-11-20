@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-slate-800 bg-slate-900/70 p-6 shadow-xl backdrop-blur-sm']) }}>
    @if ($title)
        <h3 class="text-base font-semibold text-slate-200 border-b border-slate-800/80 pb-3 mb-4">
            {{ $title }}
        </h3>
    @endif
    {{ $slot }}
</div>
