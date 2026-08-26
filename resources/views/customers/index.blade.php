<x-layouts.app :title="__('app.customers')" :header="__('app.customers')">
    <x-slot:actions>
        <x-button id="add-customer" data-action="create-customer"><x-icon name="plus" /> {{ __('app.add_customer') }}</x-button>
    </x-slot:actions>

    <x-card>
        <div class="border-b border-slate-200 p-4">
            <x-text-input type="search" id="customer-search" autocomplete="off"
                          :value="request('search')"
                          :placeholder="__('app.search_customers')" class="sm:max-w-sm" />
        </div>

        <div id="customers-table">
            @include('customers.partials.table')
        </div>
    </x-card>

    @include('customers.partials.form-modal')
</x-layouts.app>
