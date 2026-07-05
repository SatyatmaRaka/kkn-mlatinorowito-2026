<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Pemetaan Potensi UKM</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 1.5rem; font-size: 12px; }
        h1 { text-align: center; font-size: 1rem; text-transform: uppercase; margin: 0; }
        .sub { text-align: center; font-weight: bold; margin: 0.5rem 0 1rem; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 0.35rem; vertical-align: top; }
        th { background: #f5f5f5; text-align: center; font-size: 11px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:1rem"><button onclick="window.print()">Cetak</button> <a href="{{ route('panel.ukm.index') }}">Kembali</a></div>
    <h1>Pemetaan Potensi Usaha Kecil Menengah (UKM)</h1>
    <p class="sub">Desa {{ $desa }} Kecamatan {{ $kecamatan }} Kabupaten {{ $kabupaten }}</p>
    <table>
        <thead><tr><th>No</th><th>Nama UKM/Usaha</th><th>Jenis Usaha</th><th>Rata-rata Omzet/bulan</th><th>Jangkauan Pemasaran</th><th>Keterangan</th></tr></thead>
        <tbody>
        @forelse ($ukm as $item)
            <tr><td style="text-align:center">{{ $loop->iteration }}</td><td>{{ $item->nama_usaha }}</td><td>{{ $item->jenis_usaha }}</td><td>{{ $item->rata_rata_omzet ?? '' }}</td><td>{{ $item->jangkauan_pemasaran ?? '' }}</td><td>{{ $item->keterangan ?? '' }}</td></tr>
        @empty
            <tr><td colspan="6" style="text-align:center">Belum ada data.</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
