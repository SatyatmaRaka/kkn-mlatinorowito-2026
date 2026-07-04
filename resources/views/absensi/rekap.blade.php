<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold">Rekap Absensi — {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y') }}</h1>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <span class="badge bg-success-subtle text-success border border-success-subtle" x-data x-init="setInterval(() => $el.classList.toggle('opacity-50'), 1000)">
                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Live
                </span>
                <button type="button" class="btn btn-sm btn-warning rounded-pill" data-bs-toggle="modal" data-bs-target="#modal-catat-izin-sakit" onclick="window.dispatchEvent(new CustomEvent('buka-catat-modal', { detail: { anggotaId: '' } }))">
                    <i class="bi bi-pencil-square me-1"></i> Catat Izin/Sakit
                </button>
                <a href="{{ route('panel.absensi.export', ['tanggal_mulai' => $tanggal, 'tanggal_selesai' => $tanggal]) }}" class="btn btn-sm btn-success rounded-pill">
                    <i class="bi bi-download me-1"></i> Export CSV
                </a>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
        </div>
    @endif

    <x-filter-daftar placeholder="Cari nama anggota, jabatan..." :reset-url="route('panel.absensi.rekap', ['tanggal' => $tanggal])">
        <div class="col-md-2 col-lg-2">
            <label class="form-label small mb-1 fw-semibold">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control form-control-sm">
        </div>
        <div class="col-md-2 col-lg-2">
            <label class="form-label small mb-1 fw-semibold">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua</option>
                <option value="hadir" @selected(($filterStatus ?? '') === 'hadir')>Hadir</option>
                <option value="izin" @selected(($filterStatus ?? '') === 'izin')>Izin</option>
                <option value="sakit" @selected(($filterStatus ?? '') === 'sakit')>Sakit</option>
                <option value="belum" @selected(($filterStatus ?? '') === 'belum')>Belum</option>
            </select>
        </div>
    </x-filter-daftar>

    @php
        $mapHadir = $hadir->map(fn ($a) => [
            'nama' => $a->nama,
            'jabatan' => $a->jabatan,
            'jam' => ($recordsByAnggota[$a->id]->check_in_at ?? null)?->format('H:i') ?? '-',
        ])->values();
        $mapIzin = $izin->map(fn ($a) => [
            'id' => $recordsByAnggota[$a->id]->id,
            'nama' => $a->nama,
            'keterangan' => $recordsByAnggota[$a->id]->keterangan ?? '',
            'metode' => $recordsByAnggota[$a->id]->metode,
        ])->values();
        $mapSakit = $sakit->map(fn ($a) => [
            'id' => $recordsByAnggota[$a->id]->id,
            'nama' => $a->nama,
            'keterangan' => $recordsByAnggota[$a->id]->keterangan ?? '',
            'metode' => $recordsByAnggota[$a->id]->metode,
        ])->values();
        $mapBelum = $belum->map(fn ($a) => ['id' => $a->id, 'nama' => $a->nama, 'jabatan' => $a->jabatan])->values();
    @endphp

    <div
        class="mb-4"
        x-data="rekapAbsensiLive(@js($tanggal), {
            hadir: {{ $hadir->count() }},
            izin: {{ $izin->count() }},
            sakit: {{ $sakit->count() }},
            belum: {{ $belum->count() }},
            daftarHadir: @js($mapHadir),
            daftarIzin: @js($mapIzin),
            daftarSakit: @js($mapSakit),
            daftarBelum: @js($mapBelum),
        })"
        x-init="mulai()"
    >
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="premium-card border-0 p-3 p-md-4 text-center">
                    <div class="text-muted small text-uppercase fw-bold text-success">Hadir</div>
                    <div class="display-6 fw-bold text-success" x-text="hadir"></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="premium-card border-0 p-3 p-md-4 text-center">
                    <div class="text-muted small text-uppercase fw-bold text-warning">Izin</div>
                    <div class="display-6 fw-bold text-warning" x-text="izin"></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="premium-card border-0 p-3 p-md-4 text-center">
                    <div class="text-muted small text-uppercase fw-bold text-info">Sakit</div>
                    <div class="display-6 fw-bold text-info" x-text="sakit"></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="premium-card border-0 p-3 p-md-4 text-center">
                    <div class="text-muted small text-uppercase fw-bold text-secondary">Belum Ada Keterangan</div>
                    <div class="display-6 fw-bold text-secondary" x-text="belum"></div>
                </div>
            </div>
        </div>

        @if (! $filterStatus || $filterStatus === 'hadir')
            <div class="premium-card border-0 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom fw-bold text-success">Hadir</div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3">Nama</th>
                                <th class="py-3">Jabatan</th>
                                <th class="pe-4 py-3">Jam Check-in</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="daftarHadir.length === 0">
                                <tr><td colspan="3" class="text-muted text-center py-4 px-4">Belum ada yang hadir.</td></tr>
                            </template>
                            <template x-for="item in daftarHadir" :key="item.nama + item.jam">
                                <tr>
                                    <td class="ps-4 py-3 fw-semibold" x-text="item.nama"></td>
                                    <td class="py-3 text-muted" x-text="item.jabatan"></td>
                                    <td class="pe-4 py-3"><span x-text="item.jam"></span> WIB</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if (! $filterStatus || $filterStatus === 'izin')
            <div class="premium-card border-0 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom fw-bold text-warning">Izin</div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3">Nama</th>
                                <th class="py-3">Keterangan</th>
                                <th class="pe-4 py-3 text-end" style="width: 5rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="daftarIzin.length === 0">
                                <tr><td colspan="3" class="text-muted text-center py-4 px-4">Tidak ada catatan izin.</td></tr>
                            </template>
                            <template x-for="item in daftarIzin" :key="'izin-' + item.id">
                                <tr>
                                    <td class="ps-4 py-3 fw-semibold" x-text="item.nama"></td>
                                    <td class="py-3 text-muted small" x-text="item.keterangan" :title="item.keterangan"></td>
                                    <td class="pe-4 py-3 text-end">
                                        <template x-if="item.metode === 'manual'">
                                            <form :action="hapusUrl(item.id)" method="POST" class="d-inline" onsubmit="return confirm('Hapus catatan izin ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Hapus catatan">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if (! $filterStatus || $filterStatus === 'sakit')
            <div class="premium-card border-0 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom fw-bold text-info">Sakit</div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3">Nama</th>
                                <th class="py-3">Keterangan</th>
                                <th class="pe-4 py-3 text-end" style="width: 5rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="daftarSakit.length === 0">
                                <tr><td colspan="3" class="text-muted text-center py-4 px-4">Tidak ada catatan sakit.</td></tr>
                            </template>
                            <template x-for="item in daftarSakit" :key="'sakit-' + item.id">
                                <tr>
                                    <td class="ps-4 py-3 fw-semibold" x-text="item.nama"></td>
                                    <td class="py-3 text-muted small" x-text="item.keterangan" :title="item.keterangan"></td>
                                    <td class="pe-4 py-3 text-end">
                                        <template x-if="item.metode === 'manual'">
                                            <form :action="hapusUrl(item.id)" method="POST" class="d-inline" onsubmit="return confirm('Hapus catatan sakit ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Hapus catatan">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if (! $filterStatus || $filterStatus === 'belum')
            <div class="premium-card border-0 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom fw-bold text-secondary">Belum Ada Keterangan</div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3">Nama</th>
                                <th class="py-3">Jabatan</th>
                                <th class="pe-4 py-3 text-end" style="width: 8rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="daftarBelum.length === 0">
                                <tr><td colspan="3" class="text-success text-center py-4 px-4">Semua anggota sudah punya catatan.</td></tr>
                            </template>
                            <template x-for="item in daftarBelum" :key="'belum-' + item.id">
                                <tr>
                                    <td class="ps-4 py-3 fw-semibold" x-text="item.nama"></td>
                                    <td class="py-3 text-muted" x-text="item.jabatan"></td>
                                    <td class="pe-4 py-3 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#modal-catat-izin-sakit"
                                            @click="$dispatch('buka-catat-modal', { anggotaId: String(item.id) })">
                                            Catat Izin/Sakit
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <div
        class="modal fade"
        id="modal-catat-izin-sakit"
        tabindex="-1"
        aria-labelledby="modal-catat-izin-sakit-label"
        aria-hidden="true"
        x-data="{ anggotaId: '' }"
        @buka-catat-modal.window="anggotaId = $event.detail.anggotaId || ''"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="{{ route('panel.absensi.catat-izin-sakit') }}">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="modal-catat-izin-sakit-label">Catat Izin / Sakit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="catat-anggota-id" class="form-label fw-semibold">Anggota</label>
                            <select name="anggota_id" id="catat-anggota-id" class="form-select" required x-model="anggotaId">
                                <option value="">— Pilih anggota —</option>
                                @foreach ($anggotaDenganAkun as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} ({{ $item->jabatan }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="catat-tanggal" class="form-label fw-semibold">Tanggal</label>
                            <input type="date" name="tanggal" id="catat-tanggal" class="form-control" value="{{ $tanggal }}" max="{{ now()->toDateString() }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">Status</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="catat-status-izin" value="izin" required checked>
                                    <label class="form-check-label" for="catat-status-izin">Izin</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="catat-status-sakit" value="sakit" required>
                                    <label class="form-check-label" for="catat-status-sakit">Sakit</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label for="catat-keterangan" class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" id="catat-keterangan" class="form-control" rows="3" maxlength="500" required placeholder="Alasan izin atau sakit...">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning rounded-pill px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function rekapAbsensiLive(tanggal, initial) {
            return {
                tanggal,
                hadir: initial.hadir,
                izin: initial.izin,
                sakit: initial.sakit,
                belum: initial.belum,
                daftarHadir: initial.daftarHadir,
                daftarIzin: initial.daftarIzin,
                daftarSakit: initial.daftarSakit,
                daftarBelum: initial.daftarBelum,
                hapusBase: @json(url('/panel/absensi')),
                hapusUrl(id) {
                    return this.hapusBase + '/' + id + '/hapus-catatan';
                },
                async muat() {
                    try {
                        const url = @json(route('panel.api.live.absensi-rekap')) + '?tanggal=' + encodeURIComponent(this.tanggal);
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (!res.ok) return;
                        const data = await res.json();
                        this.hadir = data.hadir;
                        this.izin = data.izin;
                        this.sakit = data.sakit;
                        this.belum = data.belum;
                        this.daftarHadir = data.daftar_hadir;
                        this.daftarIzin = data.daftar_izin;
                        this.daftarSakit = data.daftar_sakit;
                        this.daftarBelum = data.daftar_belum;
                    } catch (e) {}
                },
                mulai() {
                    setInterval(() => this.muat(), 30000);
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
