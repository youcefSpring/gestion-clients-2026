@props(['title', 'hint' => null])

<div class="flex flex-col items-center justify-center gap-2 px-6 py-20 text-center">
    <p class="text-lg font-semibold text-slate-900">{{ $title }}</p>
    @if ($hint)
        <p class="text-base text-slate-500">{{ $hint }}</p>
    @endif
    @if (trim($slot) !== '')
        <div class="mt-3">{{ $slot }}</div>
    @endif
</div>
