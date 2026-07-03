<x-app-layout>
    <x-slot name="header">Surat Menyurat</x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="premium-card border-0 mb-4 p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="btn-group" role="group">
                <a href="{{ route('panel.surat.index') }}" class="btn btn-sm {{ empty($jenis) ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
                <a href="{{ route('panel.surat.index', ['jenis' => 'masuk']) }}" class="btn btn-sm {{ $jenis === 'masuk' ? 'btn-primary' : 'btn-outline-primary' }}">Masuk</a>
                <a href="{{ route('panel.surat.index', ['jenis' => 'keluar']) }}" class="btn btn-sm {{ $jenis === 'keluar' ? 'btn-primary' : 'btn-outline-primary' }}">Keluar</a>
            </div>
            <a href="{{ route('panel.surat.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="bi bi-plus-lg me-1"></i> Catat Surat
            </a>
        </div>
    </div>

    <div class="premium-card border-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3">Tanggal</th>
                        <th class="py-3">Jenis</th>
                        <th class="py-3">Nomor</th>
                        <th class="py-3">Asal / Tujuan</th>
                        <th class="py-3">Perihal</th>
                        <th class="py-3 text-center">Lampiran</th>
                        <th class="pe-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($surat as $item)
                        <tr>
                            <td class="ps-4 py-3">{{ $item->tanggal->locale('id')->translatedFormat('d M Y') }}</td>
                            <td class="py-3">
                                <span class="badge {{ $item->isMasuk() ? 'bg-info-subtle text-info border border-info-subtle' : 'bg-warning-subtle text-warning-emphasis border' }}">
                                    {{ $item->labelJenis() }}
                                </span>
                            </td>
                            <td class="py-3">{{ $item->nomor_surat ?? '—' }}</td>
                            <td class="py-3">{{ $item->asal_tujuan }}</td>
                            <td class="py-3">{{ Str::limit($item->perihal, 50) }}</td>
                            <td class="py-3 text-center">
                                @if ($item->lampiran)
                                    <a href="{{ asset('storage/' . $item->lampiran) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-light">
                                        <i class="bi bi-paperclip"></i>
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('panel.surat.edit', $item) }}" class="btn btn-sm btn-light rounded-circle text-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('panel.surat.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus arsip surat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light rounded-circle text-danger">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Belum ada surat tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($surat->hasPages())
            <div class="p-3 border-top">{{ $surat->links() }}</div>
        @endif
    </div>
</x-app-layout>
