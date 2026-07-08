<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 w-100">
            <h1 class="h4 mb-0 fw-bold text-dark">Observasi Lapangan</h1>
            <a href="{{ route('panel.observasi-lapangan.cetak') }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="bi bi-printer"></i> Cetak Lampiran 2</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('panel.observasi-lapangan.update') }}" method="POST">
        @csrf @method('PUT')

        <div class="premium-card border-0 mb-4 p-4">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:3rem" class="text-center">No</th>
                            <th>Kegiatan/Kelembagaan masyarakat yang ada</th>
                            <th style="width:8rem" class="text-center">Aktifitas</th>
                            <th style="width:22%">Permasalahan</th>
                            <th style="width:22%">Rencana Pemecahan Masalah</th>
                            <th style="width:3rem"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($observasi->items as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $item->nama_kelembagaan }}</td>
                                <td>
                                    <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                    <select name="items[{{ $loop->index }}][status]" class="form-select form-select-sm">
                                        <option value="ada" @selected(old("items.{$loop->index}.status", $item->status) === 'ada')>Ada</option>
                                        <option value="tidak" @selected(old("items.{$loop->index}.status", $item->status) === 'tidak')>Tidak</option>
                                    </select>
                                </td>
                                <td><textarea name="items[{{ $loop->index }}][permasalahan]" class="form-control form-control-sm" rows="2">{{ old("items.{$loop->index}.permasalahan", $item->permasalahan) }}</textarea></td>
                                <td><textarea name="items[{{ $loop->index }}][rencana_pemecahan_masalah]" class="form-control form-control-sm" rows="2">{{ old("items.{$loop->index}.rencana_pemecahan_masalah", $item->rencana_pemecahan_masalah) }}</textarea></td>
                                <td class="text-center">
                                    @if (!in_array($item->nama_kelembagaan, \App\Models\ObservasiLapangan::KELEMBAGAAN_WAJIB, true) && Auth::user()->canReviewLogbook())
                                        <form action="{{ route('panel.observasi-lapangan.item.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus baris ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-danger border-0">×</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="premium-card border-0 mb-4 p-4">
            <div class="mb-3">
                <label class="form-label fw-semibold">Hasil identifikasi permasalahan utama yang ada di desa</label>
                <textarea name="ringkasan_permasalahan" class="form-control" rows="4">{{ old('ringkasan_permasalahan', $observasi->ringkasan_permasalahan) }}</textarea>
            </div>
            <div class="mb-0">
                <label class="form-label fw-semibold">Rencana, metode, dan TTG yang akan digunakan untuk pemecahan masalah utama</label>
                <textarea name="rencana_pemecahan" class="form-control" rows="4">{{ old('rencana_pemecahan', $observasi->rencana_pemecahan) }}</textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Observasi</button>
        </div>
    </form>

    <div class="premium-card border-0 p-4 mb-4">
        <h2 class="h6 fw-bold mb-3">Tambah Kelembagaan Lain</h2>
        <form action="{{ route('panel.observasi-lapangan.item.store') }}" method="POST" class="row g-2 align-items-end">
            @csrf
            <div class="col-md-8">
                <label class="form-label small fw-semibold">Nama Kelembagaan</label>
                <input type="text" name="nama_kelembagaan" class="form-control" placeholder="Kelembagaan lain yang ditemukan di lapangan" required maxlength="255">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-plus-circle"></i> Tambah Kelembagaan Lain</button>
            </div>
        </form>
    </div>
</x-app-layout>
