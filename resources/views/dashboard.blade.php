<x-layouts.app :title="__('app.dashboard')" :header="__('app.dashboard')">
    <x-slot:actions>
        <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">
            <x-icon name="plus" /> {{ __('app.add_project') }}
        </a>
    </x-slot:actions>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        <x-stat-card :label="__('app.total_customers')" :value="$totalCustomers" />
        <x-stat-card :label="__('app.total_projects')" :value="$totalProjects" />
        <x-stat-card :label="__('app.new_projects')" :value="$newProjects" />
        <x-stat-card :label="__('app.confirmed_projects')" :value="$confirmedProjects" />
        <x-stat-card :label="__('app.finished_projects')" :value="$finishedProjects" />
        <x-stat-card :label="__('app.cancelled_projects')" :value="$cancelledProjects" />
    </div>

    <x-card class="mt-8">
        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="text-sm font-semibold text-slate-900">{{ __('app.latest_projects') }}</h2>
        </div>

        @if ($latestProjects->isEmpty())
            <x-empty-state :title="__('app.no_projects_title')" :hint="__('app.no_projects_hint')">
                <a href="{{ route('projects.index') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">
                    {{ __('app.add_project') }}
                </a>
            </x-empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse text-[15px] leading-6">
                    <thead class="bg-slate-100 text-xs uppercase tracking-wider text-slate-600">
                        <tr>
                            <th class="border border-slate-200 px-6 py-4 text-start font-semibold">{{ __('app.customer') }}</th>
                            <th class="border border-slate-200 px-6 py-4 text-start font-semibold">{{ __('app.project') }}</th>
                            <th class="border border-slate-200 px-6 py-4 text-start font-semibold">{{ __('app.status') }}</th>
                            <th class="border border-slate-200 px-6 py-4 text-start font-semibold">{{ __('app.date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestProjects as $project)
                            <tr class="{{ $project->status->rowClasses() }}">
                                <td class="border border-slate-200 px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $project->customer->display_name }}</div>
                                    <div class="text-xs text-slate-500" dir="ltr">{{ $project->customer->phone }}</div>
                                </td>
                                <td class="border border-slate-200 px-6 py-4 text-slate-700">{{ $project->display_name }}</td>
                                <td class="border border-slate-200 px-6 py-4"><x-status-badge :status="$project->status" /></td>
                                <td class="border border-slate-200 px-6 py-4 text-slate-500">{{ $project->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>
</x-layouts.app>
