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
        <table class="min-w-full border-collapse text-[15px] leading-6">
            <thead class="bg-slate-100 text-xs uppercase tracking-wider text-slate-600">
                <tr>
                    <th class="border border-slate-200 px-6 py-4 text-start font-semibold">{{ __('app.customer') }}</th>
                    <th class="border border-slate-200 px-6 py-4 text-start font-semibold">{{ __('app.phone') }}</th>
                    <th class="border border-slate-200 px-6 py-4 text-start font-semibold">{{ __('app.project') }}</th>
                    <th class="border border-slate-200 px-6 py-4 text-start font-semibold">{{ __('app.description') }}</th>
                    <th class="border border-slate-200 px-6 py-4 text-start font-semibold">{{ __('app.status') }}</th>
                    <th class="border border-slate-200 px-6 py-4 text-start font-semibold">{{ __('app.date') }}</th>
                    <th class="border border-slate-200 px-6 py-4 text-end font-semibold">{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr data-project-row="{{ $project->id }}" data-row class="transition-colors {{ $project->status->rowClasses() }}">
                        <td class="border border-slate-200 px-6 py-4 font-medium text-slate-900">{{ $project->customer->display_name }}</td>
                        <td class="border border-slate-200 px-6 py-4 text-slate-600" dir="ltr">{{ $project->customer->phone }}</td>
                        <td class="border border-slate-200 px-6 py-4 text-slate-900">{{ $project->display_name }}</td>
                        <td class="max-w-xs border border-slate-200 px-6 py-4 text-slate-500">{{ Str::limit($project->description, 60) }}</td>
                        <td class="border border-slate-200 px-6 py-4">
                            <select class="rounded-full border-0 px-3 py-1.5 text-sm font-medium shadow-sm ring-1 ring-inset focus:outline-none focus:ring-2 focus:ring-slate-300 {{ $project->status->classes() }}"
                                    data-action="change-status" data-id="{{ $project->id }}">
                                @foreach (\App\Enums\ProjectStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected($project->status === $status)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="border border-slate-200 px-6 py-4 text-slate-500">{{ $project->created_at->format('Y-m-d') }}</td>
                        <td class="border border-slate-200 px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
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
        <p class="border-t border-slate-100 px-5 py-2 text-xs text-slate-400">{{ __('app.hidden_archived_hint') }}</p>
    @endif

    @if ($projects->hasPages())
        <div class="border-t border-slate-200 px-6 py-4">
            {{ $projects->links() }}
        </div>
    @endif
@endif
