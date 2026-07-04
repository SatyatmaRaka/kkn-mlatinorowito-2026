<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold">Rekap Absensi — {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y') }}</h1>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <span class="badge bg-success-subtle text-success border border-success-subtle" x-data x-init="setInterval(() => $el.classList.toggle('opacity-50'), 1000)">
                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Live
                </span>
                <a href="{{ route('panel.absensi.export', ['tanggal_mulai' => $tanggal, 'tanggal_selesai' => $tanggal]) }}" class="btn btn-sm btn-success rounded-pill">
                    <i class="bi bi-download me-1"></i> Export CSV
                </a>
            </div>
        </div>
    </x-slot>

    <x-filter-daftar placeholder="Cari nama anggota, jabatan..." :reset-url="route('panel.absensi.rekap', ['tanggal' => $tanggal])">
        <div class="col-md-2 col-lg-2">
            <label class="form-label small mb-1 fw-semibold">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control form-control-sm">
        </div>
        <div class="col-md-2 col-lg-2">
            <label class="form-label small mb-1 fw-semibold">Kehadiran</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua</option>
                <option value="hadir" @selected(($filterStatus ?? '') === 'hadir')>Sudah Hadir</option>
                <option value="belum" @selected(($filterStatus ?? '') === 'belum')>Belum Hadir</option>
            </select>
        </div>
    </x-filter-daftar>

    <div
        class="row g-4 mb-4"
        x-data="rekapAbsensiLive(@js($tanggal))"
        x-init="mulai()"
    >
        <div class="col-md-4">
            <div class="premium-card border-0 p-4 text-center">
                <div class="text-muted small text-uppercase fw-bold">Total Anggota</div>
                <div class="display-6 fw-bold" x-text="total"></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="premium-card border-0 p-4 text-center">
                <div class="text-muted small text-uppercase fw-bold text-success">Hadir</div>
                <div class="display-6 fw-bold text-success" x-text="hadir"></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="premium-card border-0 p-4 text-center">
                <div class="text-muted small text-uppercase fw-bold text-danger">Belum Hadir</div>
                <div class="display-6 fw-bold text-danger" x-text="belum"></div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="premium-card border-0">
                <div class="card-header bg-white py-3 px-4 border-bottom fw-bold text-success">Sudah Absen</div>
                <ul class="list-group list-group-flush">
                    <template x-if="sudahAbsen.length === 0">
                        <li class="list-group-item text-muted px-4 py-4">Belum ada yang absen.</li>
                    </template>
                    <template x-for="item in sudahAbsen" :key="item.nama">
                        <li class="list-group-item d-flex justify-content-between px-4">
                            <span x-text="item.nama"></span>
                            <span class="text-muted" x-text="item.jam + ' WIB'"></span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="premium-card border-0">
                <div class="card-header bg-white py-3 px-4 border-bottom fw-bold text-danger">Belum Absen</div>
                <ul class="list-group list-group-flush">
                    <template x-if="belumAbsen.length === 0">
                        <li class="list-group-item text-success px-4 py-4">Semua anggota sudah absen!</li>
                    </template>
                    <template x-for="item in belumAbsen" :key="item.nama">
                        <li class="list-group-item px-4">
                            <span x-text="item.nama"></span>
                            <span class="text-muted small" x-text="'(' + item.jabatan + ')'"></span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function rekapAbsensiLive(tanggal) {
            return {
                tanggal,
                total: {{ $anggotaDenganAkun->count() }},
                hadir: {{ $hadir->count() }},
                belum: {{ $belum->count() }},
                sudahAbsen: @js($absensiHari->map(fn ($a) => ['nama' => $a->anggota->nama, 'jam' => $a->check_in_at->format('H:i')])->values()),
                belumAbsen: @js($belum->map(fn ($a) => ['nama' => $a->nama, 'jabatan' => $a->jabatan])->values()),
                async muat() {
                    try {
                        const url = @json(route('panel.api.live.absensi-rekap')) + '?tanggal=' + encodeURIComponent(this.tanggal);
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (!res.ok) return;
                        const data = await res.json();
                        this.total = data.total;
                        this.hadir = data.hadir;
                        this.belum = data.belum;
                        this.sudahAbsen = data.sudah_absen;
                        this.belumAbsen = data.belum_absen;
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
