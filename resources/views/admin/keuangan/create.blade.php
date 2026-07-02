<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.keuangan.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <span>{{ __('Tambah Catatan Keuangan') }}</span>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.keuangan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="tanggal" class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Transaksi <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis" id="jenis_pemasukan" value="pemasukan" {{ old('jenis') == 'pemasukan' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="jenis_pemasukan">
                                        Pemasukan (Uang Masuk)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis" id="jenis_pengeluaran" value="pengeluaran" {{ old('jenis', 'pengeluaran') == 'pengeluaran' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="jenis_pengeluaran">
                                        Pengeluaran (Uang Keluar)
                                    </label>
                                </div>
                            </div>
                            @error('jenis')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nominal" class="form-label fw-semibold">Nominal (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('nominal') is-invalid @enderror" id="nominal" name="nominal" value="{{ old('nominal') }}" placeholder="Contoh: 50000" min="0" required>
                            @error('nominal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label fw-semibold">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Beli cat untuk proker mural" required>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="bukti" class="form-label fw-semibold">Bukti / Nota (Opsional)</label>
                            <input class="form-control @error('bukti') is-invalid @enderror" type="file" id="bukti" name="bukti" accept="image/*">
                            <div class="form-text">Maksimal 2MB. Format: JPG, PNG, WEBP.</div>
                            @error('bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.keuangan.index') }}" class="btn btn-light">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Catatan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
