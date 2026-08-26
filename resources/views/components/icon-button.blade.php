@props(['icon', 'label', 'variant' => 'default'])

@php
    $styles = [
        'default' => 'border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-slate-900',
        'danger' => 'border-rose-200 text-rose-600 hover:bg-rose-50',
    ][$variant];
@endphp

<button type="button" title="{{ $label }}" aria-label="{{ $label }}"
        {{ $attributes->merge(['class' => 'inline-flex h-10 w-10 items-center justify-center rounded-xl border shadow-sm transition-colors '.$styles]) }}>
    <x-icon :name="$icon" class="h-[18px] w-[18px]" />
</button>
