@props(['active' => false])

<a {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-colors '.($active ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100')]) }}>
    {{ $slot }}
</a>
