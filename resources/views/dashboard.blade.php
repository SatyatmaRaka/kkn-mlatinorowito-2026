<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <p class="mb-0 fs-5">Selamat datang, {{ Auth::user()->name }}!</p>
        </div>
    </div>

    <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
        <div class="col">
            <a href="{{ route('admin.anggota.index') }}" class="text-decoration-none">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center p-3 p-md-4">
                        <div class="display-6 fw-bold text-primary mb-1">{{ $totalAnggota }}</div>
                        <div class="text-muted small">Anggota</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('admin.proker.index') }}" class="text-decoration-none">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center p-3 p-md-4">
                        <div class="display-6 fw-bold text-primary mb-1">{{ $totalProker }}</div>
                        <div class="text-muted small">Program Kerja</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('admin.kegiatan.index') }}" class="text-decoration-none">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center p-3 p-md-4">
                        <div class="display-6 fw-bold text-primary mb-1">{{ $totalKegiatan }}</div>
                        <div class="text-muted small">Kegiatan</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('admin.galeri.index') }}" class="text-decoration-none">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center p-3 p-md-4">
                        <div class="display-6 fw-bold text-primary mb-1">{{ $totalGaleri }}</div>
                        <div class="text-muted small">Galeri</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h2 class="h6 mb-0 fw-semibold">Kegiatan Terbaru</h2>
                </div>
                <div class="card-body p-0">
                    @if ($kegiatanTerbaru->isEmpty())
                        <p class="text-muted mb-0 p-4">Belum ada kegiatan.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Judul</th>
                                        <th>Tanggal</th>
                                        <th class="pe-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kegiatanTerbaru as $item)
                                        <tr>
                                            <td class="ps-3">{{ $item->judul }}</td>
                                            <td>{{ $item->tanggal->locale('id')->translatedFormat('d F Y') }}</td>
                                            <td class="pe-3">
                                                <a
                                                    href="{{ route('admin.kegiatan.edit', $item) }}"
                                                    class="btn btn-sm btn-outline-primary"
                                                >
                                                    Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h2 class="h6 mb-0 fw-semibold">Aksi Cepat</h2>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('admin.anggota.create') }}" class="btn btn-outline-primary">
                        + Tambah Anggota
                    </a>
                    <a href="{{ route('admin.proker.create') }}" class="btn btn-outline-primary">
                        + Tambah Program Kerja
                    </a>
                    <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-outline-primary">
                        + Tambah Kegiatan
                    </a>
                    <a href="{{ route('admin.galeri.create') }}" class="btn btn-outline-primary">
                        + Upload Foto
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
