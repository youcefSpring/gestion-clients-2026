<x-layouts.app :title="__('app.projects')" :header="__('app.projects')">
    <x-slot:actions>
        <x-button data-action="create-project"><x-icon name="plus" /> {{ __('app.add_project') }}</x-button>
    </x-slot:actions>

    <x-card>
        <div class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50/70 p-5 sm:flex-row sm:items-center">
            <x-text-input type="search" id="project-search" autocomplete="off"
                          :value="request('search')"
                          :placeholder="__('app.search_projects')" class="sm:max-w-sm" />

            <x-select-input id="project-status-filter" class="sm:max-w-[12rem]">
                <option value="">{{ __('app.all_statuses') }}</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </x-select-input>

            <x-select-input id="project-customer-filter" class="sm:max-w-[14rem]">
                <option value="">{{ __('app.customers') }}</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" @selected((string) $selectedCustomer === (string) $customer->id)>
                        {{ $customer->display_name }} — {{ $customer->phone }}
                    </option>
                @endforeach
            </x-select-input>

            <label class="flex items-center gap-2 text-sm text-slate-600 sm:ms-auto">
                <input type="checkbox" id="project-show-archived" value="1" class="rounded border-slate-300" @checked($showArchived)>
                {{ __('app.show_archived') }}
            </label>
        </div>

        <div id="projects-table">
            @include('projects.partials.table')
        </div>
    </x-card>

    @include('projects.partials.form-modal')
    @include('projects.partials.show-modal')
</x-layouts.app>
