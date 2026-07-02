<x-app-layout>
    <x-slot name="header">
        {{ __('Profile') }}
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                @include('profile.partials.update-profile-information-form')

                <div class="alert alert-info mt-4 mb-0">
                    Untuk mengganti username atau password, gunakan menu
                    <a href="{{ route('admin.pengaturan.index') }}" class="alert-link">Pengaturan</a>
                    di dashboard admin.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
