<x-layouts.guest :title="__('app.login')">
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div class="space-y-1">
            <label for="email" class="block text-sm font-medium text-slate-700">{{ __('app.email') }}</label>
            <x-text-input type="email" name="email" id="email" :value="old('email')" required autofocus />
            @error('email')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-1">
            <label for="password" class="block text-sm font-medium text-slate-700">{{ __('app.password') }}</label>
            <x-text-input type="password" name="password" id="password" required />
            @error('password')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember" value="1" class="rounded border-slate-300">
            {{ __('app.remember_me') }}
        </label>

        <x-button type="submit" class="w-full">{{ __('app.login') }}</x-button>

        <p class="text-center text-sm text-slate-500">
            {{ __('app.no_account') }}
            <a href="{{ route('register') }}" class="font-medium text-slate-900 underline">{{ __('app.register') }}</a>
        </p>
    </form>
</x-layouts.guest>
