<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Hadir Mingguan — Minggu {{ $mingguDipilih }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 1.5rem; color: #111; font-size: 12px; }
        h1 { text-align: center; font-size: 1.1rem; font-weight: bold; margin: 0 0 1rem; text-transform: uppercase; letter-spacing: 0.02em; }
        .info { margin-bottom: 1rem; line-height: 1.6; }
        .info p { margin: 0.15rem 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
        th, td { border: 1px solid #333; padding: 0.35rem 0.4rem; text-align: center; vertical-align: middle; }
        th { background: #f5f5f5; font-weight: bold; font-size: 11px; }
        td.nama, th.nama { text-align: left; }
        td.keterangan { text-align: left; font-size: 10px; min-width: 120px; }
        .simbol { font-weight: bold; }
        .legend { font-size: 11px; color: #444; margin-top: 0.5rem; }
        .toolbar { margin-bottom: 1rem; display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; }
        .alert { padding: 0.75rem 1rem; border-radius: 0.25rem; margin-bottom: 1rem; }
        .alert-warning { background: #fff3cd; border: 1px solid #ffecb5; color: #664d03; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0.5rem; }
        }
    </style>
</head>
<body>
    @if (session('warning'))
        <div class="alert alert-warning no-print">{{ session('warning') }}</div>
    @endif

    @if ($peringatan)
        <div class="alert alert-warning no-print">{{ $peringatan }}</div>
    @endif

    @if ($daftar)
        <div class="toolbar no-print">
            <form method="GET" action="{{ route('panel.laporan.daftar-hadir-mingguan') }}" class="d-inline">
                <label for="minggu">Minggu ke:</label>
                <select name="minggu" id="minggu" onchange="this.form.submit()">
                    @for ($i = 1; $i <= $jumlahMinggu; $i++)
                        <option value="{{ $i }}" @selected($mingguDipilih === $i)>Minggu {{ $i }}</option>
                    @endfor
                </select>
            </form>
            <button type="button" onclick="window.print()">Cetak</button>
            <a href="{{ route('panel.laporan.daftar-hadir-mingguan-pdf', ['minggu' => $mingguDipilih]) }}">Unduh PDF</a>
            <a href="{{ route('panel.laporan.index') }}">← Kembali ke Laporan</a>
        </div>

        <h1>Daftar Hadir Harian Tim KKN</h1>

        <div class="info">
            <p><strong>Desa</strong> : {{ $daftar['desa'] ?: '—' }} &nbsp;&nbsp; <strong>Kec./Kab.</strong> : {{ $daftar['kecamatan'] ?: '—' }} / {{ $daftar['kabupaten'] ?: '—' }}</p>
            <p><strong>Kordes</strong> : {{ $daftar['kordes_nama'] }} / <strong>NIM.</strong> {{ $daftar['kordes_nim'] }}</p>
            <p><strong>DPL</strong> : {{ $daftar['nama_dpl'] ?: '—' }} / <strong>NIDN.</strong> {{ $daftar['nidn_dpl'] ?: '—' }}</p>
            <p><em>Minggu ke-{{ $mingguDipilih }} ({{ $daftar['minggu']['mulai']->locale('id')->translatedFormat('d M Y') }} – {{ $daftar['minggu']['selesai']->locale('id')->translatedFormat('d M Y') }})</em></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 2.5rem;">No</th>
                    <th style="width: 6rem;">NIM</th>
                    <th class="nama">Nama</th>
                    @foreach ($daftar['tanggal'] as $tanggal)
                        <th style="width: 2.5rem;">Tgl<br>{{ $tanggal->format('d/m') }}</th>
                    @endforeach
                    <th class="keterangan">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftar['baris'] as $baris)
                    <tr>
                        <td>{{ $baris['no'] }}</td>
                        <td>{{ $baris['nim'] }}</td>
                        <td class="nama">{{ $baris['nama'] }}</td>
                        @foreach ($daftar['tanggal'] as $tanggal)
                            @php $tgl = $tanggal->toDateString(); @endphp
                            <td class="simbol">{{ $baris['simbol'][$tgl] ?? '-' }}</td>
                        @endforeach
                        <td class="keterangan">{{ $baris['keterangan'] ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 4 + count($daftar['tanggal']) }}">Belum ada anggota dengan akun.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p class="legend"><strong>Legenda:</strong> H = Hadir, I = Izin, S = Sakit, - = Belum Ada Catatan</p>
    @endif
</body>
</html>
