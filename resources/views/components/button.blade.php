@props(['variant' => 'primary', 'type' => 'button'])

@php
    $styles = [
        'primary' => 'bg-slate-900 text-white hover:bg-slate-700',
        'success' => 'bg-emerald-700 text-white hover:bg-emerald-800',
        'secondary' => 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-700',
    ][$variant];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold shadow-sm transition-colors disabled:opacity-60 '.$styles]) }}>
    {{ $slot }}
</button>
