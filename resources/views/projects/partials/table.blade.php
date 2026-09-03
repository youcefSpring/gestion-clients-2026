@php($hasFilters = filled(request('search')) || filled(request('status')) || filled(request('customer')))
@php($hidingArchived = ! request()->boolean('show_archived') && blank(request('status')))

@if ($projects->isEmpty())
    <x-empty-state :title="$hasFilters ? __('app.no_results') : __('app.no_projects_title')"
                   :hint="$hasFilters ? null : __('app.no_projects_hint')">
        @if (! $hasFilters)
            <x-button data-action="create-project"><x-icon name="plus" /> {{ __('app.add_project') }}</x-button>
        @endif
        @if ($hidingArchived)
            <p class="mt-2 text-xs text-slate-400">{{ __('app.hidden_archived_hint') }}</p>
        @endif
    </x-empty-state>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full text-[15px] leading-6">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-6 py-4 text-start font-semibold">{{ __('app.customer') }}</th>
                    <th class="px-6 py-4 text-start font-semibold">{{ __('app.project') }}</th>
                    <th class="px-6 py-4 text-start font-semibold">{{ __('app.description') }}</th>
                    <th class="px-6 py-4 text-start font-semibold">{{ __('app.status') }}</th>
                    <th class="px-6 py-4 text-start font-semibold">{{ __('app.date') }}</th>
                    <th class="px-6 py-4 text-end font-semibold">{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($projects as $project)
                    @php($digits = \App\Support\Phone::international($project->customer->phone))
                    <tr data-project-row="{{ $project->id }}" data-row class="transition-colors {{ $project->status->rowClasses() }}">
                        <td class="px-6 py-5">
                            <div class="text-base font-semibold text-slate-900">{{ $project->customer->display_name }}</div>
                            <div class="mt-0.5 text-xs text-slate-500" dir="ltr">{{ $project->customer->phone }}</div>
                        </td>
                        <td class="px-6 py-5 font-medium text-slate-800">{{ $project->display_name }}</td>
                        <td class="max-w-sm px-6 py-5">
                            <x-tag-list :text="$project->description" />
                        </td>
                        <td class="px-6 py-5">
                            <select class="rounded-full border-0 px-3 py-1.5 text-sm font-semibold shadow-sm ring-1 ring-inset focus:outline-none focus:ring-2 focus:ring-slate-300 {{ $project->status->classes() }}"
                                    data-action="change-status" data-id="{{ $project->id }}">
                                @foreach (\App\Enums\ProjectStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected($project->status === $status)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-5 text-slate-500">{{ $project->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <x-icon-button icon="eye" :label="__('app.view')"
                                               data-action="view-project"
                                               data-name="{{ $project->display_name }}"
                                               data-customer="{{ $project->customer->display_name }}"
                                               data-phone="{{ $project->customer->phone }}"
                                               data-digits="{{ $digits }}"
                                               data-status="{{ $project->status->label() }}"
                                               data-description="{{ $project->description }}"
                                               data-date="{{ $project->created_at->format('Y-m-d H:i') }}"
                                               data-customer-url="{{ route('projects.index', ['customer' => $project->customer_id, 'show_archived' => 1]) }}" />
                                @if ($digits !== '')
                                    <a href="tel:{{ $digits }}" data-action="call-phone" data-digits="{{ $digits }}" title="{{ __('app.call') }}" aria-label="{{ __('app.call') }}"
                                       class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-sky-200 text-sky-600 shadow-sm transition-colors hover:bg-sky-50">
                                        <x-icon name="phone" class="h-[18px] w-[18px]" />
                                    </a>
                                    <a href="https://wa.me/{{ $digits }}" data-action="open-whatsapp" data-digits="{{ $digits }}" target="_blank" rel="noopener"
                                       title="{{ __('app.whatsapp') }}" aria-label="{{ __('app.whatsapp') }}"
                                       class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-emerald-200 text-emerald-600 shadow-sm transition-colors hover:bg-emerald-50">
                                        <x-icon name="chat" class="h-[18px] w-[18px]" />
                                    </a>
                                @endif
                                <x-icon-button icon="edit" :label="__('app.edit')"
                                               data-action="edit-project"
                                               data-id="{{ $project->id }}"
                                               data-customer-id="{{ $project->customer_id }}"
                                               data-name="{{ $project->name }}"
                                               data-description="{{ $project->description }}"
                                               data-status="{{ $project->status->value }}" />
                                <x-icon-button icon="trash" variant="danger" :label="__('app.delete')"
                                               data-action="delete-project"
                                               data-id="{{ $project->id }}" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($hidingArchived)
        <p class="border-t border-slate-100 px-6 py-3 text-xs text-slate-400">{{ __('app.hidden_archived_hint') }}</p>
    @endif

    @if ($projects->hasPages())
        <div class="border-t border-slate-200 px-6 py-4">
            {{ $projects->links() }}
        </div>
    @endif
@endif
