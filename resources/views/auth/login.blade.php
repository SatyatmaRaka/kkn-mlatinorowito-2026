<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-3 alert alert-success py-2" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <x-input-label for="email" class="form-label" :value="__('Email')" />
            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="text-danger mt-1 mb-0" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-input-label for="password" class="form-label" :value="__('Password')" />

            <x-text-input id="password" class="form-control"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="text-danger mt-1 mb-0" />
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
        </div>

        @if (Route::has('password.request'))
            <div class="mb-3 text-end">
                <a class="text-decoration-none small" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            </div>
        @endif

        <x-primary-button class="btn btn-primary w-100">
            {{ __('Log in') }}
        </x-primary-button>
    </form>
</x-guest-layout>
