<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold text-dark">Manajemen Galeri</h1>
            <a href="{{ route('admin.galeri.create') }}" class="btn btn-primary btn-sm px-3 rounded-pill fw-medium d-flex align-items-center gap-2">
                <i class="bi bi-cloud-arrow-up-fill"></i> Upload Foto
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($galeri->isEmpty())
        <div class="premium-card border-0 mb-4">
            <div class="card-body p-5 text-center">
                <div class="d-flex flex-column align-items-center">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-images text-muted fs-1"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Belum ada foto</h6>
                    <p class="text-muted small mb-0">Klik tombol Upload Foto untuk menambahkan galeri pertama Anda.</p>
                </div>
            </div>
        </div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4 mb-4">
            @foreach ($galeri as $item)
                <div class="col">
                    <div class="premium-card h-100 border-0 shadow-sm position-relative overflow-hidden group">
                        <div class="position-relative">
                            <img
                                src="{{ asset('storage/' . $item->foto) }}"
                                alt="{{ $item->keterangan ?? 'Foto galeri' }}"
                                class="card-img-top w-100 object-fit-cover transition-all"
                                style="aspect-ratio: 1 / 1;"
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
                                    class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                    style="width: 35px; height: 35px;"
                                    aria-label="Hapus foto"
                                    data-bs-toggle="tooltip" title="Hapus"
                                >
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                        @if ($item->keterangan)
                            <div class="card-body p-3 bg-white border-top">
                                <p class="card-text small text-dark mb-0 fw-medium">{{ $item->keterangan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
