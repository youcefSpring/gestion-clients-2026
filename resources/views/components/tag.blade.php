@props(['tone' => 'slate'])

@php
    $tones = [
        'slate' => 'bg-slate-100 text-slate-600 ring-slate-200',
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'sky' => 'bg-sky-50 text-sky-700 ring-sky-200',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-md px-2.5 py-1 text-xs font-medium ring-1 ring-inset '.($tones[$tone] ?? $tones['slate'])]) }}>
    {{ $slot }}
</span>
