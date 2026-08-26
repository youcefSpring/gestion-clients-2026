@props(['title', 'hint' => null])

<div class="flex flex-col items-center justify-center gap-2 px-6 py-16 text-center">
    <p class="text-base font-medium text-slate-900">{{ $title }}</p>
    @if ($hint)
        <p class="text-sm text-slate-500">{{ $hint }}</p>
    @endif
    @if (trim($slot) !== '')
        <div class="mt-3">{{ $slot }}</div>
    @endif
</div>
