<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Logbook Harian KKN</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 1rem; font-size: 11px; color: #111; }
        h1 { text-align: center; font-size: 1rem; text-transform: uppercase; margin-bottom: 1rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 0.3rem; vertical-align: top; }
        th { background: #f5f5f5; font-size: 10px; text-align: center; }
        .foto { max-width: 60px; max-height: 45px; }
        .toolbar { margin-bottom: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>
    @unless($untukPdf ?? false)
    <div class="toolbar no-print">
        <form method="GET">
            Dari <input type="date" name="dari" value="{{ $dari }}">
            Sampai <input type="date" name="sampai" value="{{ $sampai }}">
            Anggota <select name="anggota_id"><option value="">Semua</option>
                @foreach ($anggotaList as $a)<option value="{{ $a->id }}" @selected($anggota_id == $a->id)>{{ $a->nama }}</option>@endforeach
            </select>
            <button type="submit">Filter</button>
        </form>
        <button onclick="window.print()">Cetak</button>
        <a href="{{ route('panel.laporan.logbook-harian-pdf', request()->query()) }}">Unduh PDF</a>
    </div>
    @endunless

    <h1>Logbook Harian Kegiatan KKN</h1>
    <p style="text-align:center;margin-top:-0.5rem">Periode {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Hari/Tanggal</th>
                <th>Waktu & Durasi Kegiatan</th>
                <th>Kegiatan yang Dilakukan</th>
                <th>Tempat</th>
                <th>Penanggung Jawab (PIC)</th>
                <th>Foto Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($baris as $row)
                <tr>
                    <td style="text-align:center">{{ $row['no'] }}</td>
                    <td>{{ $row['hari_tanggal'] }}</td>
                    <td>{{ $row['waktu_durasi'] }}</td>
                    <td>{{ $row['kegiatan'] }}</td>
                    <td>{{ $row['tempat'] }}</td>
                    <td>{{ $row['pic'] }}</td>
                    <td style="text-align:center">
                        @if ($row['foto'])
                            <img src="{{ asset('storage/'.$row['foto']) }}" alt="Foto" class="foto">
                        @else — @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center">Tidak ada logbook disetujui.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
