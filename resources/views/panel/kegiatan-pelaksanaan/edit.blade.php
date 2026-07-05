<x-app-layout>
    <x-slot name="header"><a href="{{ route('panel.kegiatan-pelaksanaan.show', $kegiatan) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a> Edit Kegiatan</x-slot>
    <div class="row"><div class="col-lg-8"><div class="card border-0 shadow-sm"><div class="card-body p-4">
        <form action="{{ route('panel.kegiatan-pelaksanaan.update', $kegiatan) }}" method="POST">@csrf @method('PUT')
            <div class="mb-3"><label class="form-label fw-semibold">Nama Kegiatan *</label><input name="nama_kegiatan" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" class="form-control" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Tanggal *</label><input type="date" name="tanggal" value="{{ old('tanggal', $kegiatan->tanggal->format('Y-m-d')) }}" class="form-control" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Tempat *</label><input name="tempat" value="{{ old('tempat', $kegiatan->tempat) }}" class="form-control" required></div>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="form-label fw-semibold">Waktu Mulai *</label><input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', substr($kegiatan->waktu_mulai, 0, 5)) }}" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Waktu Selesai *</label><input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', substr($kegiatan->waktu_selesai, 0, 5)) }}" class="form-control" required></div>
            </div>
            <div class="mb-4"><label class="form-label fw-semibold">PIC Kegiatan</label>
                <select name="pic_anggota_id" class="form-select"><option value="">—</option>@foreach($anggotaList as $a)<option value="{{ $a->id }}" @selected(old('pic_anggota_id', $kegiatan->pic_anggota_id)==$a->id)>{{ $a->nama }}</option>@endforeach</select>
            </div>
            <div class="d-flex justify-content-end gap-2"><a href="{{ route('panel.kegiatan-pelaksanaan.show', $kegiatan) }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Perbarui</button></div>
        </form>
    </div></div></div></div>
</x-app-layout>
