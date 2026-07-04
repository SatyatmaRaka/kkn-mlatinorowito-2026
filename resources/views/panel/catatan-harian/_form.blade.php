<div class="row g-3">
    <div class="col-md-4">
        <label for="tanggal" class="form-label">Tanggal</label>
        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', isset($logbook) ? $logbook->tanggal->format('Y-m-d') : now()->format('Y-m-d')) }}" class="form-control @error('tanggal') is-invalid @enderror" required>
        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="jam_mulai" class="form-label">Jam Mulai</label>
        <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', $logbook->jam_mulai ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label for="jam_selesai" class="form-label">Jam Selesai</label>
        <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', $logbook->jam_selesai ?? '') }}" class="form-control">
    </div>
    <div class="col-12">
        <label for="judul" class="form-label">Judul Kegiatan</label>
        <input type="text" id="judul" name="judul" value="{{ old('judul', $logbook->judul ?? '') }}" class="form-control @error('judul') is-invalid @enderror" required>
        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="lokasi" class="form-label">Lokasi</label>
        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $logbook->lokasi ?? '') }}" class="form-control" placeholder="Contoh: Posko KKN / RT 03">
    </div>
    <div class="col-12">
        <label for="deskripsi" class="form-label">Deskripsi Kegiatan</label>
        <textarea id="deskripsi" name="deskripsi" rows="6" class="form-control @error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi', $logbook->deskripsi ?? '') }}</textarea>
        @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="foto" class="form-label">Foto Dokumentasi (opsional)</label>
        <input type="file" id="foto" name="foto" accept="image/*" class="form-control">
        @if (!empty($logbook?->foto))
            <img src="{{ asset('storage/' . $logbook->foto) }}" alt="Foto logbook" class="mt-2 rounded" style="max-height: 120px;" loading="lazy" decoding="async">
        @endif
    </div>
</div>
