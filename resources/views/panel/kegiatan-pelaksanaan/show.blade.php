<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 w-100">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('panel.kegiatan-pelaksanaan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
                <span>{{ $kegiatan->nama_kegiatan }}</span>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('panel.kegiatan-pelaksanaan.cetak-masyarakat', $kegiatan) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Cetak Peserta</a>
                <a href="{{ route('panel.kegiatan-pelaksanaan.cetak-tim', $kegiatan) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Cetak Tim</a>
                @can('update', $kegiatan)<a href="{{ route('panel.kegiatan-pelaksanaan.edit', $kegiatan) }}" class="btn btn-sm btn-primary">Edit</a>@endcan
            </div>
        </div>
    </x-slot>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    <div class="premium-card border-0 mb-4 p-4">
        <p><strong>Tanggal:</strong> {{ $kegiatan->tanggal->format('d/m/Y') }} · <strong>Tempat:</strong> {{ $kegiatan->tempat }}</p>
        <p><strong>Waktu:</strong> {{ substr($kegiatan->waktu_mulai,0,5) }} – {{ substr($kegiatan->waktu_selesai,0,5) }} WIB · <strong>PIC:</strong> {{ $kegiatan->pic?->nama ?? '—' }}</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="premium-card border-0 p-4">
                <h2 class="h6 fw-bold">Peserta Masyarakat</h2>
                <table class="table table-sm"><thead><tr><th>Nama</th><th>Alamat</th><th></th></tr></thead><tbody>
                @forelse ($kegiatan->pesertaMasyarakat as $p)
                    <tr><td>{{ $p->nama }}</td><td>{{ $p->alamat ?? '—' }}</td><td>
                        @if (Auth::user()->canReviewLogbook())
                        <form action="{{ route('panel.kegiatan-pelaksanaan.peserta.destroy', [$kegiatan, $p]) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm text-danger border-0">×</button></form>
                        @endif
                    </td></tr>
                @empty<tr><td colspan="3" class="text-muted">Belum ada peserta.</td></tr>@endforelse
                </tbody></table>
                <form action="{{ route('panel.kegiatan-pelaksanaan.peserta.store', $kegiatan) }}" method="POST" class="row g-2">@csrf
                    <div class="col-md-5"><input name="nama" class="form-control form-control-sm" placeholder="Nama" required></div>
                    <div class="col-md-5"><input name="alamat" class="form-control form-control-sm" placeholder="Alamat"></div>
                    <div class="col-md-2"><button class="btn btn-sm btn-primary w-100">+</button></div>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="premium-card border-0 p-4">
                <h2 class="h6 fw-bold">Tugas Tim</h2>
                <table class="table table-sm"><thead><tr><th>Anggota</th><th>Tugas</th><th></th></tr></thead><tbody>
                @forelse ($kegiatan->tugasTim as $t)
                    <tr><td>{{ $t->anggota->nama }}</td><td>{{ $t->tugas }}</td><td>
                        @if (Auth::user()->canReviewLogbook())
                        <form action="{{ route('panel.kegiatan-pelaksanaan.tugas.destroy', [$kegiatan, $t]) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm text-danger border-0">×</button></form>
                        @endif
                    </td></tr>
                @empty<tr><td colspan="3" class="text-muted">Belum ada tugas.</td></tr>@endforelse
                </tbody></table>
                <form action="{{ route('panel.kegiatan-pelaksanaan.tugas.store', $kegiatan) }}" method="POST" class="row g-2">@csrf
                    <div class="col-md-5"><select name="anggota_id" class="form-select form-select-sm" required><option value="">Anggota</option>@foreach($anggotaList as $a)<option value="{{ $a->id }}">{{ $a->nama }}</option>@endforeach</select></div>
                    <div class="col-md-5"><input name="tugas" class="form-control form-control-sm" placeholder="Tugas" required></div>
                    <div class="col-md-2"><button class="btn btn-sm btn-primary w-100">+</button></div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
