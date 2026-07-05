<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Keaktifan KKN</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 1.5rem; font-size: 12px; color: #111; }
        h1 { text-align: center; font-size: 1.1rem; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        th, td { border: 1px solid #333; padding: 0.4rem; text-align: center; }
        th { background: #f5f5f5; }
        td.nama { text-align: left; }
        .catatan { font-size: 11px; font-style: italic; margin-top: 0.5rem; }
        .toolbar { margin-bottom: 1rem; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>
    @unless($untukPdf ?? false)
    <div class="toolbar no-print">
        <button onclick="window.print()">Cetak</button>
        <a href="{{ route('panel.laporan.rekap-keaktifan-pdf') }}">Unduh PDF</a>
        <a href="{{ route('panel.laporan.index') }}">← Kembali</a>
    </div>
    @endunless

    <h1>Rekap Keaktifan Anggota KKN</h1>
    <p style="text-align:center;font-weight:bold;text-transform:uppercase">
        Desa {{ $pengaturan->get('desa', '—') }} · Kec. {{ $pengaturan->get('kecamatan', '—') }} · Kab. {{ $pengaturan->get('kabupaten', '—') }}
    </p>

    <table>
        <thead>
            <tr><th>No</th><th>NIM</th><th class="nama">Nama</th><th>Total Jam</th></tr>
        </thead>
        <tbody>
            @forelse ($baris as $row)
                <tr>
                    <td>{{ $row['no'] }}</td>
                    <td>{{ $row['nim'] }}</td>
                    <td class="nama">{{ $row['nama'] }}</td>
                    <td>{{ number_format($row['total_jam'], 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
    <p class="catatan">Catatan: Total Jam dihitung dari Logbook berstatus Disetujui.</p>
</body>
</html>
