<div class="row g-3">
    @php
        use App\Enums\KategoriTujuanSurat;
        use App\Penunjang\PenerimaSurat;

        $jenisDefault = old('jenis', $surat->jenis ?? ($defaultJenis ?? ''));
        $isKeluar = $jenisDefault === 'keluar';
        $kategoriDefault = old('kategori_tujuan', $surat->kategori_tujuan ?? ($defaultKategori ?? KategoriTujuanSurat::Kelurahan->value));
    @endphp
    <div class="col-md-6">
        <label class="form-label">Jenis Surat</label>
        <select name="jenis" id="jenis-surat" class="form-select @error('jenis') is-invalid @enderror" required>
            <option value="masuk" @selected($jenisDefault === 'masuk')>Surat Masuk</option>
            <option value="keluar" @selected($jenisDefault === 'keluar')>Surat Keluar</option>
        </select>
        @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Nomor Surat</label>
        <input type="text" name="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror" value="{{ old('nomor_surat', $surat->nomor_surat ?? '') }}" placeholder="{{ $isKeluar ? 'Kosongkan untuk nomor otomatis' : 'Opsional' }}">
        @error('nomor_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if ($isKeluar)
            <div class="form-text">Biarkan kosong — sistem generate nomor otomatis saat disimpan.</div>
        @endif
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', isset($surat) ? $surat->tanggal->format('Y-m-d') : now()->toDateString()) }}" required>
        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Surat keluar: pilih kategori tujuan --}}
    <div class="col-md-6 field-keluar-only" @unless($isKeluar) style="display:none" @endunless>
        <label class="form-label">Tujuan Surat</label>
        <select name="kategori_tujuan" id="kategori-tujuan" class="form-select @error('kategori_tujuan') is-invalid @enderror">
            @foreach (KategoriTujuanSurat::cases() as $kategori)
                <option value="{{ $kategori->value }}" @selected($kategoriDefault === $kategori->value)>{{ $kategori->label() }}</option>
            @endforeach
        </select>
        @error('kategori_tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Surat masuk: asal surat --}}
    <div class="col-md-6 field-masuk-only" @unless(! $isKeluar) style="display:none" @endunless>
        <label class="form-label">Asal Surat</label>
        <input type="text" name="asal_tujuan" id="asal-masuk" class="form-control @error('asal_tujuan') is-invalid @enderror" value="{{ old('asal_tujuan', $surat->asal_tujuan ?? '') }}" placeholder="Pengirim surat masuk">
        @error('asal_tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- RT / RW --}}
    <div class="col-md-3 field-rt" @unless($isKeluar && in_array($kategoriDefault, ['rt'], true)) style="display:none" @endunless>
        <label class="form-label">Nomor RT</label>
        <input type="text" name="nomor_rt" id="nomor-rt" class="form-control @error('nomor_rt') is-invalid @enderror" value="{{ old('nomor_rt', $surat->nomor_rt ?? '') }}" placeholder="Contoh: 03">
        @error('nomor_rt')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3 field-rw-keluar" @unless($isKeluar && in_array($kategoriDefault, ['rt', 'rw'], true)) style="display:none" @endunless>
        <label class="form-label">Nomor RW @if($kategoriDefault === 'rt')<span class="text-muted fw-normal">(opsional)</span>@endif</label>
        <input type="text" name="nomor_rw" id="nomor-rw" class="form-control @error('nomor_rw') is-invalid @enderror" value="{{ old('nomor_rw', $surat->nomor_rw ?? '') }}" placeholder="Contoh: 05">
        @error('nomor_rw')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Instansi manual --}}
    <div class="col-12 field-instansi" @unless($isKeluar && $kategoriDefault === 'instansi') style="display:none" @endunless>
        <label class="form-label">Nama Instansi / Penerima</label>
        <input type="text" name="asal_tujuan" id="asal-instansi" class="form-control @error('asal_tujuan') is-invalid @enderror" value="{{ old('asal_tujuan', ($surat->kategori_tujuan ?? null) === 'instansi' ? ($surat->asal_tujuan ?? '') : old('asal_tujuan')) }}" placeholder="Contoh: Dinas Pendidikan Kabupaten Kudus">
        @error('asal_tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Pratinjau penerima surat keluar --}}
    <div class="col-12 field-keluar-only" id="preview-penerima-wrap" @unless($isKeluar) style="display:none" @endunless>
        <div class="alert alert-light border small mb-0">
            <strong>Penerima surat:</strong>
            <span id="preview-penerima">{{ PenerimaSurat::teksPenerima($kategoriDefault, old('nomor_rt', $surat->nomor_rt ?? ''), old('nomor_rw', $surat->nomor_rw ?? ''), old('asal_tujuan')) }}</span>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label">Perihal</label>
        <input type="text" name="perihal" class="form-control @error('perihal') is-invalid @enderror" value="{{ old('perihal', $surat->perihal ?? '') }}" required>
        @error('perihal')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">{{ $isKeluar ? 'Isi Surat' : 'Keterangan' }}</label>
        <textarea name="keterangan" rows="{{ $isKeluar ? 8 : 3 }}" class="form-control @error('keterangan') is-invalid @enderror" placeholder="{{ $isKeluar ? 'Tulis isi surat keluar. Pisahkan paragraf dengan baris kosong.' : 'Opsional' }}" {{ $isKeluar ? 'required' : '' }}>{{ old('keterangan', $surat->keterangan ?? '') }}</textarea>
        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    @if ($isKeluar)
        <div class="col-12 field-keluar-only">
            <div class="alert alert-success border-0 small mb-0">
                <i class="bi bi-file-earmark-pdf me-1"></i>
                Setelah disimpan, sistem otomatis <strong>membuat PDF surat resmi</strong> siap unduh — tidak perlu upload file manual.
            </div>
        </div>
    @else
        <div class="col-12 field-masuk-only">
            <label class="form-label">Lampiran Scan (PDF/Gambar, maks. 5 MB)</label>
            <input type="file" name="lampiran" class="form-control @error('lampiran') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png,.webp">
            @error('lampiran')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @if (! empty($surat?->lampiran))
                <div class="form-text">
                    Lampiran saat ini:
                    <a href="{{ asset('storage/' . $surat->lampiran) }}" target="_blank" rel="noopener noreferrer">Lihat file</a>
                </div>
            @endif
        </div>
    @endif
</div>

@once
    @push('scripts')
        <script>
            (function () {
                const kelurahan = @json(PenerimaSurat::teksPenerima(KategoriTujuanSurat::Kelurahan));
                const namaKelurahan = @json(PenerimaSurat::NAMA_KELURAHAN);

                function teksPenerima(kategori, rt, rw, instansi) {
                    rt = (rt || '').trim();
                    rw = (rw || '').trim();
                    instansi = (instansi || '').trim();

                    if (kategori === 'kelurahan') return kelurahan;
                    if (kategori === 'rt') {
                        if (rt && rw) return 'Ketua RT ' + rt + ' RW ' + rw + ' ' + namaKelurahan;
                        if (rt) return 'Ketua RT ' + rt + ' ' + namaKelurahan;
                        return 'Ketua RT … ' + namaKelurahan;
                    }
                    if (kategori === 'rw') {
                        return rw ? 'Ketua RW ' + rw + ' ' + namaKelurahan : 'Ketua RW … ' + namaKelurahan;
                    }
                    return instansi || '…';
                }

                function toggleFields() {
                    const jenis = document.getElementById('jenis-surat')?.value;
                    const isKeluar = jenis === 'keluar';
                    const kategori = document.getElementById('kategori-tujuan')?.value;

                    document.querySelectorAll('.field-keluar-only').forEach(el => el.style.display = isKeluar ? '' : 'none');
                    document.querySelectorAll('.field-masuk-only').forEach(el => el.style.display = isKeluar ? 'none' : '');

                    const rtField = document.querySelector('.field-rt');
                    const rwField = document.querySelector('.field-rw-keluar');
                    const instansiField = document.querySelector('.field-instansi');

                    if (rtField) rtField.style.display = isKeluar && kategori === 'rt' ? '' : 'none';
                    if (rwField) rwField.style.display = isKeluar && (kategori === 'rt' || kategori === 'rw') ? '' : 'none';
                    if (instansiField) instansiField.style.display = isKeluar && kategori === 'instansi' ? '' : 'none';

                    const asalMasuk = document.getElementById('asal-masuk');
                    const asalInstansi = document.getElementById('asal-instansi');
                    if (asalMasuk) asalMasuk.disabled = isKeluar;
                    if (asalInstansi) asalInstansi.disabled = !isKeluar || kategori !== 'instansi';

                    updatePreview();
                }

                function updatePreview() {
                    const preview = document.getElementById('preview-penerima');
                    if (!preview) return;

                    const kategori = document.getElementById('kategori-tujuan')?.value;
                    const rt = document.getElementById('nomor-rt')?.value;
                    const rw = document.getElementById('nomor-rw')?.value;
                    const instansi = document.getElementById('asal-instansi')?.value;

                    preview.textContent = teksPenerima(kategori, rt, rw, instansi);
                }

                document.getElementById('jenis-surat')?.addEventListener('change', toggleFields);
                document.getElementById('kategori-tujuan')?.addEventListener('change', toggleFields);
                document.getElementById('nomor-rt')?.addEventListener('input', updatePreview);
                document.getElementById('nomor-rw')?.addEventListener('input', updatePreview);
                document.getElementById('asal-instansi')?.addEventListener('input', updatePreview);

                toggleFields();
            })();
        </script>
    @endpush
@endonce
