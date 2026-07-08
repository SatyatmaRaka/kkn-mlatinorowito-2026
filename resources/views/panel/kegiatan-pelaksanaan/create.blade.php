<x-app-layout>
    <x-slot name="header"><a href="{{ route('panel.kegiatan-pelaksanaan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a> Tambah Kegiatan</x-slot>
    <div class="row"><div class="col-lg-10"><div class="card border-0 shadow-sm"><div class="card-body p-4">
        <form action="{{ route('panel.kegiatan-pelaksanaan.store') }}" method="POST">@csrf
            <div class="row g-3 mb-3">
                <div class="col-md-8"><label class="form-label fw-semibold">Nama Kegiatan *</label><input name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Tanggal *</label><input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Tema Kegiatan</label><input name="tema_kegiatan" value="{{ old('tema_kegiatan') }}" class="form-control"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Tempat *</label><input name="tempat" value="{{ old('tempat') }}" class="form-control" required></div>
                <div class="col-md-3"><label class="form-label fw-semibold">Waktu Mulai *</label><input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', '08:00') }}" class="form-control" required></div>
                <div class="col-md-3"><label class="form-label fw-semibold">Waktu Selesai *</label><input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', '12:00') }}" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label fw-semibold">PIC Kegiatan</label>
                    <select name="pic_anggota_id" class="form-select"><option value="">—</option>@foreach($anggotaList as $a)<option value="{{ $a->id }}">{{ $a->nama }}</option>@endforeach</select>
                </div>
                <div class="col-12"><label class="form-label fw-semibold">Latar Belakang</label><textarea name="latar_belakang" class="form-control" rows="3">{{ old('latar_belakang') }}</textarea></div>
                <div class="col-12"><label class="form-label fw-semibold">Kondisi yang Mendukung</label><textarea name="kondisi_mendukung" class="form-control" rows="3">{{ old('kondisi_mendukung') }}</textarea></div>
                <div class="col-12"><label class="form-label fw-semibold">Manfaat/Tujuan</label><textarea name="manfaat_tujuan" class="form-control" rows="3">{{ old('manfaat_tujuan') }}</textarea></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Sumber Dana Masyarakat (Rp)</label><input type="number" name="sumber_dana_masyarakat" value="{{ old('sumber_dana_masyarakat', 0) }}" class="form-control" min="0"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Sumber Dana Mahasiswa (Rp)</label><input type="number" name="sumber_dana_mahasiswa" value="{{ old('sumber_dana_mahasiswa', 0) }}" class="form-control" min="0"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Sumber Dana Donatur (Rp)</label><input type="number" name="sumber_dana_donatur" value="{{ old('sumber_dana_donatur', 0) }}" class="form-control" min="0"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Keterangan Sumber Donatur</label><input name="sumber_dana_donatur_keterangan" value="{{ old('sumber_dana_donatur_keterangan') }}" class="form-control"></div>
            </div>
            <h2 class="h6 fw-bold mt-4">Peserta Masyarakat (opsional)</h2>
            @for ($i = 0; $i < 3; $i++)
                <div class="row g-2 mb-2">
                    <div class="col-md-5"><input name="peserta_nama[]" class="form-control form-control-sm" placeholder="Nama peserta"></div>
                    <div class="col-md-7"><input name="peserta_alamat[]" class="form-control form-control-sm" placeholder="Alamat"></div>
                </div>
            @endfor
            <h2 class="h6 fw-bold mt-4">Tugas Tim (opsional)</h2>
            @for ($i = 0; $i < 3; $i++)
                <div class="row g-2 mb-2">
                    <div class="col-md-5"><select name="tugas_anggota_id[]" class="form-select form-select-sm"><option value="">Anggota</option>@foreach($anggotaList as $a)<option value="{{ $a->id }}">{{ $a->nama }}</option>@endforeach</select></div>
                    <div class="col-md-7"><input name="tugas_deskripsi[]" class="form-control form-control-sm" placeholder="Tugas"></div>
                </div>
            @endfor
            <div class="mt-4 d-flex justify-content-end gap-2"><a href="{{ route('panel.kegiatan-pelaksanaan.index') }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan</button></div>
        </form>
    </div></div></div></div>
</x-app-layout>
