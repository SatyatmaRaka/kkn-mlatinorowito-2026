<x-app-layout>
    <x-slot name="header">Tulis Logbook</x-slot>

    <div class="premium-card border-0 p-4">
        <form action="{{ route('panel.catatan-harian.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('panel.catatan-harian._form')
            <div class="d-flex gap-2 mt-4">
                <button type="submit" name="submit" value="0" class="btn btn-outline-secondary rounded-pill px-4">Simpan Draft</button>
                <button type="submit" name="submit" value="1" class="btn btn-primary rounded-pill px-4">Kirim untuk Review</button>
            </div>
        </form>
    </div>
</x-app-layout>
