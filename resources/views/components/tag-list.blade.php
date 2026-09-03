@props(['text' => null, 'limit' => 4, 'tone' => 'slate'])

@php
    $tags = collect(preg_split('/[,\n;]+/', (string) $text))
        ->map(fn ($tag) => trim($tag))
        ->filter()
        ->take($limit);
@endphp

@if ($tags->isEmpty())
    <span class="text-sm text-slate-400">—</span>
@else
    <div class="flex flex-wrap gap-1.5">
        @foreach ($tags as $tag)
            <x-tag :tone="$tone">{{ \Illuminate\Support\Str::limit($tag, 28) }}</x-tag>
        @endforeach
    </div>
@endif
