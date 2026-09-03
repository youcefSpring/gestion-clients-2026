@props(['label', 'value', 'tone' => 'slate'])

@php
    $tones = [
        'slate' => ['ring-slate-200', 'text-slate-900', 'bg-slate-50'],
        'amber' => ['ring-amber-300', 'text-amber-600', 'bg-amber-50'],
        'emerald' => ['ring-emerald-300', 'text-emerald-600', 'bg-emerald-50'],
        'sky' => ['ring-sky-300', 'text-sky-600', 'bg-sky-50'],
        'indigo' => ['ring-indigo-300', 'text-indigo-600', 'bg-indigo-50'],
        'rose' => ['ring-rose-300', 'text-rose-600', 'bg-rose-50'],
    ];
    [$ring, $text, $bar] = $tones[$tone] ?? $tones['slate'];
@endphp

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-inset {{ $ring }}">
    <div class="h-1.5 {{ $bar }}"></div>
    <div class="px-6 py-7 text-center">
        <p class="text-4xl font-bold tracking-tight {{ $text }} sm:text-5xl">{{ $value }}</p>
        <p class="mt-3 text-sm font-medium text-slate-500">{{ $label }}</p>
    </div>
</div>
