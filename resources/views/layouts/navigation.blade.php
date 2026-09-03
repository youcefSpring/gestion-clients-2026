@php
    $links = [
        ['route' => 'dashboard', 'href' => route('dashboard'), 'icon' => 'dashboard', 'label' => __('app.dashboard'), 'pattern' => 'dashboard'],
        ['route' => 'customers', 'href' => route('customers.index'), 'icon' => 'users', 'label' => __('app.customers'), 'pattern' => 'customers.*'],
        ['route' => 'projects', 'href' => route('projects.index'), 'icon' => 'folder', 'label' => __('app.projects'), 'pattern' => 'projects.*'],
    ];
@endphp

<nav class="border-b border-slate-200 bg-white">
    <div class="flex w-full items-center justify-between gap-4 px-[1cm] py-4">
        <div class="flex items-center gap-6">
            <a href="{{ route('dashboard') }}" class="text-lg font-bold tracking-tight text-slate-900">
                {{ __('app.app_name') }}
            </a>
            <div class="hidden items-center gap-1 sm:flex">
                @foreach ($links as $link)
                    <x-nav-link :href="$link['href']" :active="request()->routeIs($link['pattern'])">
                        <x-icon :name="$link['icon']" /> {{ $link['label'] }}
                    </x-nav-link>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-2">
            <div class="flex items-center rounded-lg border border-slate-200 p-0.5 text-xs">
                @foreach (config('app.supported_locales') as $locale)
                    <a href="{{ route('locale', $locale) }}"
                       class="rounded-md px-2 py-1 uppercase {{ app()->getLocale() === $locale ? 'bg-slate-900 text-white' : 'text-slate-500 hover:text-slate-900' }}">
                        {{ $locale }}
                    </a>
                @endforeach
            </div>

            <a href="{{ route('profile.edit') }}" title="{{ __('app.edit_profile') }}" aria-label="{{ __('app.edit_profile') }}"
               class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 shadow-sm transition-colors hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('profile.*') ? 'bg-slate-900 text-white hover:bg-slate-700 hover:text-white' : '' }}">
                <x-icon name="user" />
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="{{ __('app.logout') }}" aria-label="{{ __('app.logout') }}"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 shadow-sm transition-colors hover:bg-slate-100 hover:text-slate-900">
                    <x-icon name="logout" />
                </button>
            </form>
        </div>
    </div>

    <div class="flex items-center gap-1 border-t border-slate-200 px-4 py-2 sm:hidden">
        @foreach ($links as $link)
            <x-nav-link :href="$link['href']" :active="request()->routeIs($link['pattern'])">
                <x-icon :name="$link['icon']" /> {{ $link['label'] }}
            </x-nav-link>
        @endforeach
    </div>
</nav>
