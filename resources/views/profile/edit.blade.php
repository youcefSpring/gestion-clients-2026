<x-layouts.app :title="__('app.profile')" :header="__('app.edit_profile')">
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <x-card class="p-6">
            <h2 class="text-sm font-semibold text-slate-900">{{ __('app.profile_info') }}</h2>

            <form method="POST" action="{{ route('profile.update') }}" class="mt-4 space-y-4">
                @csrf
                @method('PUT')

                <div class="space-y-1">
                    <label for="profile_name" class="block text-sm font-medium text-slate-700">{{ __('app.name') }}</label>
                    <x-text-input type="text" name="name" id="profile_name" :value="old('name', $user->name)" required />
                    @error('name')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1">
                    <label for="profile_email" class="block text-sm font-medium text-slate-700">{{ __('app.email') }}</label>
                    <x-text-input type="email" name="email" id="profile_email" :value="old('email', $user->email)" required dir="ltr" />
                    @error('email')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <x-button type="submit">
                    <x-icon name="check" /> {{ __('app.save') }}
                </x-button>
            </form>
        </x-card>

        <x-card class="p-6">
            <h2 class="text-sm font-semibold text-slate-900">{{ __('app.change_password') }}</h2>

            <form method="POST" action="{{ route('profile.password') }}" class="mt-4 space-y-4">
                @csrf
                @method('PUT')

                <div class="space-y-1">
                    <label for="current_password" class="block text-sm font-medium text-slate-700">{{ __('app.current_password') }}</label>
                    <x-text-input type="password" name="current_password" id="current_password" autocomplete="current-password" />
                    @error('current_password', 'password')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1">
                    <label for="new_password" class="block text-sm font-medium text-slate-700">{{ __('app.new_password') }}</label>
                    <x-text-input type="password" name="password" id="new_password" autocomplete="new-password" />
                    @error('password', 'password')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1">
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">{{ __('app.confirm_password') }}</label>
                    <x-text-input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" />
                </div>

                <x-button type="submit">
                    <x-icon name="check" /> {{ __('app.update') }}
                </x-button>
            </form>
        </x-card>
    </div>
</x-layouts.app>
