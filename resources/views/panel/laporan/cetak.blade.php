<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan KKN — {{ $ringkasan['periode']['label'] }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; color: #111; }
        h1 { font-size: 1.25rem; margin-bottom: 0.25rem; }
        .meta { color: #555; margin-bottom: 1.5rem; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
        th, td { border: 1px solid #ccc; padding: 0.5rem 0.75rem; text-align: left; }
        th { background: #f5f5f5; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <button class="no-print" onclick="window.print()">Cetak / Simpan PDF</button>
    <h1>Laporan Operasional KKN Mlatinorowito 2026</h1>
    <p class="meta">Periode: {{ $ringkasan['periode']['label'] }} · Dicetak {{ now()->locale('id')->translatedFormat('d F Y H:i') }} WIB</p>

    <h2>Ringkasan</h2>
    <table>
        <tbody>
            <tr><th>Total Absensi</th><td>{{ $ringkasan['absensi']['total'] }}</td></tr>
            <tr><th>Absensi Hari Ini</th><td>{{ $ringkasan['absensi']['hari_ini'] }}</td></tr>
            <tr><th>Logbook Menunggu Review</th><td>{{ $ringkasan['logbook']['submitted'] }}</td></tr>
            <tr><th>Logbook Disetujui</th><td>{{ $ringkasan['logbook']['approved'] }}</td></tr>
            <tr><th>Logbook Ditolak</th><td>{{ $ringkasan['logbook']['rejected'] }}</td></tr>
            @isset($ringkasan['keuangan'])
                <tr><th>Pemasukan (Rp)</th><td>{{ number_format($ringkasan['keuangan']['pemasukan'], 0, ',', '.') }}</td></tr>
                <tr><th>Pengeluaran (Rp)</th><td>{{ number_format($ringkasan['keuangan']['pengeluaran'], 0, ',', '.') }}</td></tr>
                <tr><th>Saldo Periode (Rp)</th><td>{{ number_format($ringkasan['keuangan']['saldo'], 0, ',', '.') }}</td></tr>
            @endisset
        </tbody>
    </table>
</body>
</html>
