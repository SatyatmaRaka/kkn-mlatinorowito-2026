<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('panel.buku-tamu.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
            <span>Edit Tamu</span>
        </div>
    </x-slot>

    <div class="row"><div class="col-lg-8">
        <div class="card border-0 shadow-sm"><div class="card-body p-4">
            <form action="{{ route('panel.buku-tamu.update', $bukuTamu) }}" method="POST">@csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $bukuTamu->tanggal->format('Y-m-d')) }}" class="form-control @error('tanggal') is-invalid @enderror" required>
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Tamu <span class="text-danger">*</span></label>
                    <input type="text" name="nama_tamu" value="{{ old('nama_tamu', $bukuTamu->nama_tamu) }}" class="form-control @error('nama_tamu') is-invalid @enderror" required>
                    @error('nama_tamu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat / Jabatan</label>
                    <input type="text" name="alamat_jabatan" value="{{ old('alamat_jabatan', $bukuTamu->alamat_jabatan) }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Keperluan <span class="text-danger">*</span></label>
                    <textarea name="keperluan" rows="3" class="form-control @error('keperluan') is-invalid @enderror" required>{{ old('keperluan', $bukuTamu->keperluan) }}</textarea>
                    @error('keperluan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Mahasiswa yang Menemui</label>
                    <select name="anggota_id" class="form-select">
                        <option value="">— Pilih —</option>
                        @foreach ($anggotaList as $a)
                            <option value="{{ $a->id }}" @selected(old('anggota_id', $bukuTamu->anggota_id) == $a->id)>{{ $a->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('panel.buku-tamu.index') }}" class="btn btn-light">Batal</a>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div></div>
    </div></div>
</x-app-layout>
