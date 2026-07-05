<x-app-layout>
    <x-slot name="header"><a href="{{ route('panel.ukm.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a> Tambah UKM</x-slot>
    <div class="row"><div class="col-lg-8"><div class="card border-0 shadow-sm"><div class="card-body p-4">
        <form action="{{ route('panel.ukm.store') }}" method="POST">@csrf
            <div class="mb-3"><label class="form-label fw-semibold">Nama UKM/Usaha *</label><input name="nama_usaha" value="{{ old('nama_usaha') }}" class="form-control" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Jenis Usaha *</label><input name="jenis_usaha" value="{{ old('jenis_usaha') }}" class="form-control" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Rata-rata Omzet/bulan</label><input name="rata_rata_omzet" value="{{ old('rata_rata_omzet') }}" class="form-control" placeholder="Contoh: Rp 2-5 juta"></div>
            <div class="mb-3"><label class="form-label fw-semibold">Jangkauan Pemasaran</label><input name="jangkauan_pemasaran" value="{{ old('jangkauan_pemasaran') }}" class="form-control"></div>
            <div class="mb-3"><label class="form-label fw-semibold">Keterangan</label><textarea name="keterangan" rows="3" class="form-control">{{ old('keterangan') }}</textarea></div>
            <div class="mb-4"><label class="form-label fw-semibold">Urutan</label><input type="number" name="urutan" value="{{ old('urutan', 0) }}" class="form-control" min="0" required></div>
            <div class="d-flex justify-content-end gap-2"><a href="{{ route('panel.ukm.index') }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan</button></div>
        </form>
    </div></div></div></div>
</x-app-layout>
