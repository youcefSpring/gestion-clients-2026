@if ($customers->isEmpty())
    <x-empty-state :title="request('search') ? __('app.no_results') : __('app.no_customers_title')"
                   :hint="request('search') ? null : __('app.no_customers_hint')">
        @if (! request('search'))
            <x-button data-action="create-customer">{{ __('app.add_customer') }}</x-button>
        @endif
    </x-empty-state>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse text-[15px] leading-6">
            <thead class="bg-slate-100 text-xs uppercase tracking-wider text-slate-600">
                <tr>
                    <th class="border border-slate-200 px-6 py-4 text-start font-semibold">{{ __('app.customer_name') }}</th>
                    <th class="border border-slate-200 px-6 py-4 text-start font-semibold">{{ __('app.phone') }}</th>
                    <th class="border border-slate-200 px-6 py-4 text-start font-semibold">{{ __('app.projects_count') }}</th>
                    <th class="border border-slate-200 px-6 py-4 text-end font-semibold">{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr data-customer-row="{{ $customer->id }}" class="bg-white transition-colors hover:bg-slate-50">
                        <td class="border border-slate-200 px-6 py-4">
                            <span class="font-medium {{ $customer->name ? 'text-slate-900' : 'text-slate-400 italic' }}">
                                {{ $customer->display_name }}
                            </span>
                        </td>
                        <td class="border border-slate-200 px-6 py-4">
                            <span class="inline-flex items-center gap-2 font-medium text-slate-900" dir="ltr">
                                <x-icon name="phone" class="h-4 w-4 text-slate-400" />{{ $customer->phone }}
                            </span>
                        </td>
                        <td class="border border-slate-200 px-6 py-4 text-slate-500">{{ $customer->projects_count }}</td>
                        <td class="border border-slate-200 px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('projects.index', ['customer' => $customer->id]) }}"
                                   title="{{ __('app.view_projects') }}" aria-label="{{ __('app.view_projects') }}"
                                   class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 shadow-sm transition-colors hover:bg-slate-100 hover:text-slate-900">
                                    <x-icon name="eye" class="h-[18px] w-[18px]" />
                                </a>
                                <x-icon-button icon="edit" :label="__('app.edit')"
                                               data-action="edit-customer"
                                               data-id="{{ $customer->id }}"
                                               data-name="{{ $customer->name }}"
                                               data-phone="{{ $customer->phone }}" />
                                <x-icon-button icon="trash" variant="danger" :label="__('app.delete')"
                                               data-action="delete-customer"
                                               data-id="{{ $customer->id }}" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($customers->hasPages())
        <div class="border-t border-slate-200 px-6 py-4">
            {{ $customers->links() }}
        </div>
    @endif
@endif
