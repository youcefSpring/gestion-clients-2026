<!DOCTYPE html>
@php($rtl = in_array(app()->getLocale(), config('app.rtl_locales', []), true))
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? __('app.app_name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-10 text-slate-800 antialiased">
    <div class="w-full max-w-sm">
        <div class="mb-6 text-center">
            <h1 class="text-xl font-semibold text-slate-900">{{ __('app.app_name') }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ __('app.sign_in_subtitle') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
