<x-modal id="project-modal" :title="__('app.new_project')">
    <form id="project-form" class="space-y-4" novalidate>
        @csrf
        <input type="hidden" name="id">
        <input type="hidden" name="customer_mode" value="existing">

        <div class="space-y-2">
            <span class="block text-sm font-medium text-slate-700">
                {{ __('app.customer') }}<span class="text-rose-600"> *</span>
            </span>

            <div class="inline-flex rounded-xl border border-slate-200 bg-slate-50 p-1 text-sm">
                <button type="button" data-customer-mode="existing"
                        class="rounded-lg px-3 py-1.5 font-medium transition-colors">
                    {{ __('app.existing_customer') }}
                </button>
                <button type="button" data-customer-mode="new"
                        class="rounded-lg px-3 py-1.5 font-medium transition-colors">
                    {{ __('app.new_customer') }}
                </button>
            </div>

            <div data-customer-panel="existing" class="space-y-1">
                <x-select-input name="customer_id" id="customer_id">
                    <option value="">{{ __('app.select_customer') }}</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->display_name }} — {{ $customer->phone }}</option>
                    @endforeach
                </x-select-input>
                <p class="hidden text-xs text-rose-600" data-error="customer_id"></p>
            </div>

            <div data-customer-panel="new" class="hidden space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                <x-field name="customer_name" :label="__('app.customer_name')">
                    <x-text-input type="text" name="customer_name" id="customer_name" autocomplete="off" />
                </x-field>

                <x-field name="customer_phone" :label="__('app.phone')" :required="true">
                    <x-text-input type="text" name="customer_phone" id="customer_phone" dir="ltr" autocomplete="off" />
                </x-field>
            </div>
        </div>

        <x-field name="name" :label="__('app.project_name')">
            <x-text-input type="text" name="name" id="project_name" autocomplete="off" />
        </x-field>

        <x-field name="description" :label="__('app.description')">
            <x-textarea-input name="description" id="description" rows="3"></x-textarea-input>
        </x-field>

        <x-field name="status" :label="__('app.status')" :required="true">
            <x-select-input name="status" id="status">
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </x-select-input>
        </x-field>

        <div class="flex justify-end gap-2 pt-2">
            <x-button variant="secondary" data-modal-close>{{ __('app.cancel') }}</x-button>
            <x-button type="submit"><x-icon name="check" /> {{ __('app.save') }}</x-button>
        </div>
    </form>
</x-modal>
