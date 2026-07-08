<x-app-layout>
    <x-slot name="header"><a href="{{ route('panel.kegiatan-pelaksanaan.show', $kegiatan) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a> Edit Kegiatan</x-slot>
    <div class="row"><div class="col-lg-8"><div class="card border-0 shadow-sm"><div class="card-body p-4">
        <form action="{{ route('panel.kegiatan-pelaksanaan.update', $kegiatan) }}" method="POST">@csrf @method('PUT')
            <div class="mb-3"><label class="form-label fw-semibold">Nama Kegiatan *</label><input name="nama_kegiatan" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" class="form-control" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Tema Kegiatan</label><input name="tema_kegiatan" value="{{ old('tema_kegiatan', $kegiatan->tema_kegiatan) }}" class="form-control"></div>
            <div class="mb-3"><label class="form-label fw-semibold">Tanggal *</label><input type="date" name="tanggal" value="{{ old('tanggal', $kegiatan->tanggal->format('Y-m-d')) }}" class="form-control" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Tempat *</label><input name="tempat" value="{{ old('tempat', $kegiatan->tempat) }}" class="form-control" required></div>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="form-label fw-semibold">Waktu Mulai *</label><input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', substr($kegiatan->waktu_mulai, 0, 5)) }}" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Waktu Selesai *</label><input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', substr($kegiatan->waktu_selesai, 0, 5)) }}" class="form-control" required></div>
            </div>
            <div class="mb-3"><label class="form-label fw-semibold">PIC Kegiatan</label>
                <select name="pic_anggota_id" class="form-select"><option value="">—</option>@foreach($anggotaList as $a)<option value="{{ $a->id }}" @selected(old('pic_anggota_id', $kegiatan->pic_anggota_id)==$a->id)>{{ $a->nama }}</option>@endforeach</select>
            </div>
            <div class="mb-3"><label class="form-label fw-semibold">Latar Belakang</label><textarea name="latar_belakang" class="form-control" rows="3">{{ old('latar_belakang', $kegiatan->latar_belakang) }}</textarea></div>
            <div class="mb-3"><label class="form-label fw-semibold">Kondisi yang Mendukung</label><textarea name="kondisi_mendukung" class="form-control" rows="3">{{ old('kondisi_mendukung', $kegiatan->kondisi_mendukung) }}</textarea></div>
            <div class="mb-3"><label class="form-label fw-semibold">Manfaat/Tujuan</label><textarea name="manfaat_tujuan" class="form-control" rows="3">{{ old('manfaat_tujuan', $kegiatan->manfaat_tujuan) }}</textarea></div>
            <div class="row g-3 mb-3">
                <div class="col-md-4"><label class="form-label fw-semibold">Sumber Dana Masyarakat (Rp)</label><input type="number" name="sumber_dana_masyarakat" value="{{ old('sumber_dana_masyarakat', $kegiatan->sumber_dana_masyarakat) }}" class="form-control" min="0"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Sumber Dana Mahasiswa (Rp)</label><input type="number" name="sumber_dana_mahasiswa" value="{{ old('sumber_dana_mahasiswa', $kegiatan->sumber_dana_mahasiswa) }}" class="form-control" min="0"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Sumber Dana Donatur (Rp)</label><input type="number" name="sumber_dana_donatur" value="{{ old('sumber_dana_donatur', $kegiatan->sumber_dana_donatur) }}" class="form-control" min="0"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Keterangan Sumber Donatur</label><input name="sumber_dana_donatur_keterangan" value="{{ old('sumber_dana_donatur_keterangan', $kegiatan->sumber_dana_donatur_keterangan) }}" class="form-control"></div>
            </div>
            <div class="mb-4"><p class="small text-muted mb-0">Total anggaran terhitung: <strong>Rp {{ number_format($kegiatan->total_anggaran, 0, ',', '.') }}</strong></p></div>
            <div class="d-flex justify-content-end gap-2"><a href="{{ route('panel.kegiatan-pelaksanaan.show', $kegiatan) }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Perbarui</button></div>
        </form>
    </div></div></div></div>
</x-app-layout>
