@props(['name', 'label', 'required' => false])

<div class="space-y-1">
    <label for="{{ $attributes->get('id', $name) }}" class="block text-sm font-medium text-slate-700">
        {{ $label }}@if ($required)<span class="text-rose-600"> *</span>@endif
    </label>
    {{ $slot }}
    <p class="hidden text-xs text-rose-600" data-error="{{ $name }}"></p>
</div>
