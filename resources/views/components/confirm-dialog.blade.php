<div id="confirm-dialog" data-modal class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-lg" role="alertdialog" aria-modal="true">
        <p data-confirm-message class="text-sm text-slate-700"></p>
        <div class="mt-6 flex justify-end gap-2">
            <button type="button" data-modal-close class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                {{ __('app.cancel') }}
            </button>
            <button type="button" data-confirm-accept class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-700">
                {{ __('app.confirm') }}
            </button>
        </div>
    </div>
</div>
