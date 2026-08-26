<x-layouts.guest :title="__('app.register')">
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="space-y-1">
            <label for="name" class="block text-sm font-medium text-slate-700">{{ __('app.name') }}</label>
            <x-text-input type="text" name="name" id="name" :value="old('name')" required autofocus />
            @error('name')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-1">
            <label for="email" class="block text-sm font-medium text-slate-700">{{ __('app.email') }}</label>
            <x-text-input type="email" name="email" id="email" :value="old('email')" required />
            @error('email')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-1">
            <label for="password" class="block text-sm font-medium text-slate-700">{{ __('app.password') }}</label>
            <x-text-input type="password" name="password" id="password" required />
            @error('password')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-1">
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">{{ __('app.confirm_password') }}</label>
            <x-text-input type="password" name="password_confirmation" id="password_confirmation" required />
        </div>

        <x-button type="submit" class="w-full">{{ __('app.register') }}</x-button>

        <p class="text-center text-sm text-slate-500">
            {{ __('app.have_account') }}
            <a href="{{ route('login') }}" class="font-medium text-slate-900 underline">{{ __('app.login') }}</a>
        </p>
    </form>
</x-layouts.guest>
