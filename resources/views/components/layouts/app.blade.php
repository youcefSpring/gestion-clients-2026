<!DOCTYPE html>
@php($rtl = in_array(app()->getLocale(), config('app.rtl_locales', []), true))
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? __('app.app_name') }} — {{ __('app.app_name') }}</title>
    <script>
        window.App = {
            routes: {
                customers: @json(route('customers.index')),
                projects: @json(route('projects.index')),
                projectStatus: @json(route('projects.status', ['project' => ':id'])),
            },
            messages: {
                error: @json(__('app.something_went_wrong')),
                newCustomer: @json(__('app.new_customer')),
                editCustomer: @json(__('app.edit_customer')),
                newProject: @json(__('app.new_project')),
                editProject: @json(__('app.edit_project')),
                confirmDeleteCustomer: @json(__('app.confirm_delete_customer')),
                confirmDeleteProject: @json(__('app.confirm_delete_project')),
            },
        };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <main class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-900">{{ $header ?? ($title ?? '') }}</h1>
                    @isset($subheader)
                        <p class="mt-1 text-sm text-slate-500">{{ $subheader }}</p>
                    @endisset
                </div>
                @isset($actions)
                    <div class="flex items-center gap-2">{{ $actions }}</div>
                @endisset
            </div>

            {{ $slot }}
        </main>
    </div>

    <div id="toasts" class="fixed bottom-4 end-4 z-50 flex w-72 flex-col gap-2"></div>
    <x-confirm-dialog />
</body>
</html>
