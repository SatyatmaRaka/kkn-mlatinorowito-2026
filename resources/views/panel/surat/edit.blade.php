<x-app-layout>
    <x-slot name="header">Edit Surat</x-slot>

    <div class="premium-card border-0 p-4" style="max-width: 800px;">
        <form method="POST" action="{{ route('panel.surat.update', $surat) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('panel.surat._form', ['surat' => $surat])
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Perbarui</button>
                <a href="{{ route('panel.surat.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
