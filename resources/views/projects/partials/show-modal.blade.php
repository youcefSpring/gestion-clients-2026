<x-modal id="project-show-modal" :title="__('app.project_details')">
    <dl class="divide-y divide-slate-100 text-[15px]">
        <div class="flex gap-4 py-3">
            <dt class="w-40 shrink-0 text-sm font-medium text-slate-500">{{ __('app.project') }}</dt>
            <dd class="font-semibold text-slate-900" data-show="name"></dd>
        </div>
        <div class="flex gap-4 py-3">
            <dt class="w-40 shrink-0 text-sm font-medium text-slate-500">{{ __('app.customer') }}</dt>
            <dd class="text-slate-800" data-show="customer"></dd>
        </div>
        <div class="flex gap-4 py-3">
            <dt class="w-40 shrink-0 text-sm font-medium text-slate-500">{{ __('app.phone') }}</dt>
            <dd class="text-slate-800" dir="ltr" data-show="phone"></dd>
        </div>
        <div class="flex gap-4 py-3">
            <dt class="w-40 shrink-0 text-sm font-medium text-slate-500">{{ __('app.status') }}</dt>
            <dd class="text-slate-800" data-show="status"></dd>
        </div>
        <div class="flex gap-4 py-3">
            <dt class="w-40 shrink-0 text-sm font-medium text-slate-500">{{ __('app.description') }}</dt>
            <dd class="flex flex-wrap gap-1.5" data-show="tags"></dd>
        </div>
        <div class="flex gap-4 py-3">
            <dt class="w-40 shrink-0 text-sm font-medium text-slate-500">{{ __('app.date') }}</dt>
            <dd class="text-slate-800" data-show="date"></dd>
        </div>
    </dl>

    <div class="mt-6 flex flex-wrap items-center justify-end gap-2">
        <a href="#" data-show="customer-url" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            <x-icon name="folder" class="h-4 w-4" /> {{ __('app.view_projects') }}
        </a>
        <a href="#" data-show="call" data-action="call-phone" class="inline-flex items-center gap-2 rounded-xl border border-sky-200 px-4 py-2.5 text-sm font-semibold text-sky-700 hover:bg-sky-50">
            <x-icon name="phone" class="h-4 w-4" /> {{ __('app.call') }}
        </a>
        <a href="#" data-show="whatsapp" data-action="open-whatsapp" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">
            <x-icon name="chat" class="h-4 w-4" /> {{ __('app.whatsapp') }}
        </a>
        <x-button variant="secondary" data-modal-close>{{ __('app.close') }}</x-button>
    </div>
</x-modal>
