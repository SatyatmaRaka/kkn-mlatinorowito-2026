<x-app-layout>
    @php
        $isKeluar = ($jenis ?? null) === 'keluar';
        $isMasuk = ($jenis ?? null) === 'masuk';
    @endphp
    <x-slot name="header">
        @if ($isKeluar)
            Buat Surat Keluar
        @elseif ($isMasuk)
            Catat Surat Masuk
        @else
            Buat / Catat Surat
        @endif
    </x-slot>

    <div class="premium-card border-0 p-4" style="max-width: 800px;">
        @if ($isKeluar)
            <div class="alert alert-info border-0 small mb-4">
                <strong>Surat keluar dari sistem:</strong> isi formulir → sistem generate PDF resmi → langsung unduh. Tidak perlu buat di Word lalu upload.
            </div>
        @elseif ($isMasuk)
            <div class="alert alert-light border small mb-4">
                Catat surat masuk yang diterima. Opsional lampirkan scan/foto surat fisik.
            </div>
        @endif

        <form method="POST" action="{{ route('panel.surat.store') }}" enctype="multipart/form-data">
            @csrf
            @include('panel.surat._form', ['defaultJenis' => $jenis ?? null, 'defaultKategori' => $kategori ?? null])
            <div class="d-flex gap-2 mt-4 flex-wrap">
                <button type="submit" class="btn btn-primary">
                    @if ($isKeluar)
                        <i class="bi bi-file-earmark-pdf me-1"></i> Buat & Generate PDF
                    @else
                        Simpan
                    @endif
                </button>
                <a href="{{ route('panel.surat.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
