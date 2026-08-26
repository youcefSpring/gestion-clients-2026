@props(['status'])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full px-3 py-1.5 text-xs font-semibold ring-1 ring-inset '.$status->classes()]) }}>
    <x-icon :name="$status->icon()" class="h-3.5 w-3.5" />
    {{ $status->label() }}
</span>
