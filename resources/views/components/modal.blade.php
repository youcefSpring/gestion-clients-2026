@props(['id', 'title' => ''])

<div id="{{ $id }}" data-modal class="fixed inset-0 z-40 hidden items-center justify-center bg-slate-900/40 p-[1cm]">
    <div class="w-full max-w-lg rounded-2xl bg-white p-7 shadow-lg" role="dialog" aria-modal="true">
        <div class="mb-4 flex items-start justify-between gap-4">
            <h2 data-modal-title class="text-lg font-semibold text-slate-900">{{ $title }}</h2>
            <button type="button" data-modal-close class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700" aria-label="{{ __('app.cancel') }}"><x-icon name="close" /></button>
        </div>
        {{ $slot }}
    </div>
</div>
