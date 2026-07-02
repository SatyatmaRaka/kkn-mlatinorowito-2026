<x-app-layout>
    <x-slot name="header">Edit Logbook</x-slot>

    <div class="premium-card border-0 p-4">
        <form action="{{ route('admin.logbook.update', $logbook) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.logbook._form', ['logbook' => $logbook])
            <div class="d-flex gap-2 mt-4">
                @if (in_array($logbook->status, ['draft', 'rejected']))
                    <button type="submit" name="submit" value="0" class="btn btn-outline-secondary rounded-pill px-4">Simpan</button>
                    <button type="submit" name="submit" value="1" class="btn btn-primary rounded-pill px-4">Kirim Ulang</button>
                @else
                    <button type="submit" name="submit" value="0" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                @endif
            </div>
        </form>
        @if ($logbook->catatan_reviewer)
            <div class="alert alert-warning border-0 mt-4 mb-0">
                <strong>Catatan reviewer:</strong> {{ $logbook->catatan_reviewer }}
            </div>
        @endif
    </div>
</x-app-layout>
