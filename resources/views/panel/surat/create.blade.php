<x-app-layout>
    <x-slot name="header">Catat Surat Baru</x-slot>

    <div class="premium-card border-0 p-4" style="max-width: 800px;">
        <form method="POST" action="{{ route('panel.surat.store') }}" enctype="multipart/form-data">
            @csrf
            @include('panel.surat._form')
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('panel.surat.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
