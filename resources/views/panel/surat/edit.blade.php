<x-app-layout>
    <x-slot name="header">Edit Surat</x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($surat->jenis === 'keluar' && $surat->lampiran)
        <div class="premium-card border-0 p-4 mb-4" style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h2 class="h6 fw-bold mb-1"><i class="bi bi-file-earmark-pdf text-success me-2"></i>Dokumen PDF (digenerate sistem)</h2>
                    <p class="text-muted small mb-0">Nomor: <strong>{{ $surat->nomor_surat ?? '—' }}</strong></p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('panel.surat.unduh', $surat) }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-download me-1"></i> Unduh PDF
                    </a>
                    <a href="{{ route('panel.surat.cetak', $surat) }}" target="_blank" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-eye me-1"></i> Pratinjau
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="premium-card border-0 p-4" style="max-width: 800px;">
        <form method="POST" action="{{ route('panel.surat.update', $surat) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('panel.surat._form', ['surat' => $surat])
            <div class="d-flex gap-2 mt-4 flex-wrap">
                <button type="submit" class="btn btn-primary">
                    {{ $surat->jenis === 'keluar' ? 'Simpan & Generate PDF Ulang' : 'Perbarui' }}
                </button>
                @if ($surat->jenis === 'keluar' && $surat->lampiran)
                    <a href="{{ route('panel.surat.unduh', $surat) }}" class="btn btn-success">
                        <i class="bi bi-download me-1"></i> Unduh PDF
                    </a>
                @endif
                <a href="{{ route('panel.surat.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
