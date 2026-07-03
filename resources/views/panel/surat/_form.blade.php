<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Jenis Surat</label>
        <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
            <option value="masuk" @selected(old('jenis', $surat->jenis ?? '') === 'masuk')>Surat Masuk</option>
            <option value="keluar" @selected(old('jenis', $surat->jenis ?? '') === 'keluar')>Surat Keluar</option>
        </select>
        @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Nomor Surat</label>
        <input type="text" name="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror" value="{{ old('nomor_surat', $surat->nomor_surat ?? '') }}" placeholder="Opsional">
        @error('nomor_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', isset($surat) ? $surat->tanggal->format('Y-m-d') : now()->toDateString()) }}" required>
        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Asal / Tujuan</label>
        <input type="text" name="asal_tujuan" class="form-control @error('asal_tujuan') is-invalid @enderror" value="{{ old('asal_tujuan', $surat->asal_tujuan ?? '') }}" placeholder="Pengirim (masuk) atau tujuan (keluar)" required>
        @error('asal_tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Perihal</label>
        <input type="text" name="perihal" class="form-control @error('perihal') is-invalid @enderror" value="{{ old('perihal', $surat->perihal ?? '') }}" required>
        @error('perihal')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Opsional">{{ old('keterangan', $surat->keterangan ?? '') }}</textarea>
        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Lampiran (PDF/Gambar, maks. 5 MB)</label>
        <input type="file" name="lampiran" class="form-control @error('lampiran') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png,.webp">
        @error('lampiran')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if (! empty($surat?->lampiran))
            <div class="form-text">
                Lampiran saat ini:
                <a href="{{ asset('storage/' . $surat->lampiran) }}" target="_blank" rel="noopener noreferrer">Lihat file</a>
            </div>
        @endif
    </div>
</div>
