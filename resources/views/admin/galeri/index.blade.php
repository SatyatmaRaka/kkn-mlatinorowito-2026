<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <span>Manajemen Galeri</span>
            <a href="{{ route('admin.galeri.create') }}" class="btn btn-primary btn-sm">
                + Upload Foto
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($galeri->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body p-4 text-center text-muted">
                Belum ada foto. Klik Upload Foto untuk menambahkan.
            </div>
        </div>
    @else
        <div class="row row-cols-2 row-cols-md-4 g-3">
            @foreach ($galeri as $item)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img
                                src="{{ asset('storage/' . $item->foto) }}"
                                alt="{{ $item->keterangan ?? 'Foto galeri' }}"
                                class="card-img-top rounded-top w-100"
                                style="aspect-ratio: 1 / 1; object-fit: cover;"
                            >
                            <form
                                action="{{ route('admin.galeri.destroy', $item->id) }}"
                                method="POST"
                                class="position-absolute top-0 end-0 m-2"
                                onsubmit="return confirm('Yakin ingin menghapus foto ini?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px;"
                                    aria-label="Hapus foto"
                                >&times;</button>
                            </form>
                        </div>
                        @if ($item->keterangan)
                            <div class="card-body p-2">
                                <p class="card-text small text-muted mb-0">{{ $item->keterangan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
