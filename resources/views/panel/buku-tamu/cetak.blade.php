<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Tamu KKN</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 1.5rem; font-size: 12px; color: #111; }
        h1 { text-align: center; font-size: 1.1rem; text-transform: uppercase; margin: 0 0 0.25rem; }
        .sub { text-align: center; font-weight: bold; margin-bottom: 1rem; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 0.35rem; vertical-align: top; }
        th { background: #f5f5f5; text-align: center; font-size: 11px; }
        .ttd { min-width: 80px; height: 40px; }
        .toolbar { margin-bottom: 1rem; }
        @media print { .no-print { display: none !important; } body { margin: 0.5rem; } }
    </style>
</head>
<body>
    <div class="toolbar no-print">
        <form method="GET" class="d-inline">
            <label>Dari</label> <input type="date" name="dari" value="{{ $dari }}">
            <label>Sampai</label> <input type="date" name="sampai" value="{{ $sampai }}">
            <button type="submit">Filter</button>
        </form>
        <button type="button" onclick="window.print()">Cetak</button>
        <a href="{{ route('panel.buku-tamu.index') }}">← Kembali</a>
    </div>

    <h1>Daftar Tamu KKN</h1>
    <p class="sub">Desa {{ $desa ?: '—' }} Kecamatan {{ $kecamatan ?: '—' }} Kabupaten {{ $kabupaten ?: '—' }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Hari, Tanggal</th>
                <th>Nama Tamu</th>
                <th>Alamat/Jabatan Tamu</th>
                <th>Keperluan</th>
                <th>Mhs Yang Menemui</th>
                <th>TTD Tamu</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tamu as $item)
                <tr>
                    <td style="text-align:center">{{ $loop->iteration }}</td>
                    <td>{{ $item->tanggal->locale('id')->translatedFormat('l, d/m/Y') }}</td>
                    <td>{{ $item->nama_tamu }}</td>
                    <td>{{ $item->alamat_jabatan ?? '' }}</td>
                    <td>{{ $item->keperluan }}</td>
                    <td>{{ $item->anggota?->nama ?? '' }}</td>
                    <td class="ttd"></td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
