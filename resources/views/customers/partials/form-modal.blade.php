<x-modal id="customer-modal" :title="__('app.new_customer')">
    <form id="customer-form" class="space-y-4" novalidate>
        @csrf
        <input type="hidden" name="id">

        <x-field name="name" :label="__('app.customer_name')">
            <x-text-input type="text" name="name" id="name" autocomplete="off" />
        </x-field>

        <x-field name="phone" :label="__('app.phone')" :required="true">
            <x-text-input type="text" name="phone" id="phone" dir="ltr" autocomplete="off" />
        </x-field>

        <div class="flex justify-end gap-2 pt-2">
            <x-button variant="secondary" data-modal-close>{{ __('app.cancel') }}</x-button>
            <x-button type="submit">{{ __('app.save') }}</x-button>
        </div>
    </form>
</x-modal>
