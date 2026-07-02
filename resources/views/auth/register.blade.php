<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <x-input-label for="name" class="form-label" :value="__('Name')" />
            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="text-danger mt-1 mb-0" />
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <x-input-label for="email" class="form-label" :value="__('Email')" />
            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="text-danger mt-1 mb-0" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-input-label for="password" class="form-label" :value="__('Password')" />

            <x-text-input id="password" class="form-control"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="text-danger mt-1 mb-0" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <x-input-label for="password_confirmation" class="form-label" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="form-control"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger mt-1 mb-0" />
        </div>

        <div class="mb-3 text-center">
            <a class="text-decoration-none small" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
        </div>

        <x-primary-button class="btn btn-primary w-100">
            {{ __('Register') }}
        </x-primary-button>
    </form>
</x-guest-layout>
