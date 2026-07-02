<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <p class="mb-0 fs-5">Selamat datang, {{ Auth::user()->name }}!</p>
        </div>
    </div>
</x-app-layout>
