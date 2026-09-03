<x-layouts.app :title="__('app.dashboard')" :header="__('app.dashboard')">
    <x-slot:actions>
        <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">
            <x-icon name="plus" /> {{ __('app.add_project') }}
        </a>
    </x-slot:actions>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        <x-stat-card :label="__('app.total_customers')" :value="$totalCustomers" tone="indigo" />
        <x-stat-card :label="__('app.total_projects')" :value="$totalProjects" tone="slate" />
        <x-stat-card :label="__('app.new_projects')" :value="$newProjects" tone="amber" />
        <x-stat-card :label="__('app.confirmed_projects')" :value="$confirmedProjects" tone="sky" />
        <x-stat-card :label="__('app.finished_projects')" :value="$finishedProjects" tone="emerald" />
        <x-stat-card :label="__('app.cancelled_projects')" :value="$cancelledProjects" tone="rose" />
    </div>

    <x-card class="mt-10">
        <div class="border-b border-slate-200 bg-slate-50/70 px-6 py-5">
            <h2 class="text-base font-semibold text-slate-900">{{ __('app.latest_projects') }}</h2>
        </div>

        @if ($latestProjects->isEmpty())
            <x-empty-state :title="__('app.no_projects_title')" :hint="__('app.no_projects_hint')">
                <a href="{{ route('projects.index') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">
                    {{ __('app.add_project') }}
                </a>
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
                        @foreach ($latestProjects as $project)
                            <tr class="{{ $project->status->rowClasses() }}">
                                <td class="px-6 py-5">
                                    <div class="text-base font-semibold text-slate-900">{{ $project->customer->display_name }}</div>
                                    <div class="text-xs text-slate-500" dir="ltr">{{ $project->customer->phone }}</div>
                                </td>
                                <td class="px-6 py-5 font-medium text-slate-800">{{ $project->display_name }}</td>
                                <td class="max-w-sm px-6 py-5">
                                    <x-tag-list :text="$project->description" />
                                </td>
                                <td class="px-6 py-5"><x-status-badge :status="$project->status" /></td>
                                <td class="px-6 py-5 text-slate-500">{{ $project->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-5">
                                    @php($digits = \App\Support\Phone::international($project->customer->phone))
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('projects.index', ['customer' => $project->customer_id, 'show_archived' => 1]) }}"
                                           class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-800">
                                            <x-icon name="eye" class="h-4 w-4" /> {{ __('app.view') }}
                                        </a>
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
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>
</x-layouts.app>
