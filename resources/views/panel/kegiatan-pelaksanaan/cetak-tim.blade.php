<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Hadir Tim KKN</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 1.5rem; font-size: 12px; }
        h1 { text-align: center; font-size: 1rem; text-transform: uppercase; margin: 0; }
        .sub { text-align: center; font-weight: bold; margin: 0.5rem 0 1rem; }
        .info p { margin: 0.2rem 0; }
        table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        th, td { border: 1px solid #333; padding: 0.35rem; }
        th { background: #f5f5f5; text-align: center; }
        .ttd { min-width: 90px; height: 36px; }
        .footer { display: flex; justify-content: space-between; margin-top: 2rem; }
        .footer-box { width: 45%; text-align: center; }
        .footer-box .line { border-bottom: 1px solid #333; margin: 3rem 1rem 0.5rem; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:1rem"><button onclick="window.print()">Cetak</button></div>
    <h1>Daftar Hadir Tim KKN</h1>
    <p class="sub">{{ $kegiatan->nama_kegiatan }}</p>
    <div class="info">
        <p><strong>Desa/Kec.Kab.:</strong> {{ $desa }} / {{ $kecamatan }} / {{ $kabupaten }}</p>
        <p><strong>Tanggal kegiatan:</strong> {{ $kegiatan->tanggal->locale('id')->translatedFormat('l, d F Y') }}</p>
        <p><strong>Tempat kegiatan:</strong> {{ $kegiatan->tempat }}</p>
        <p><strong>Waktu kegiatan:</strong> {{ substr($kegiatan->waktu_mulai,0,5) }} WIB s/d {{ substr($kegiatan->waktu_selesai,0,5) }} WIB</p>
    </div>
    <table>
        <thead><tr><th>No</th><th>NIM</th><th>Nama</th><th>Tugas</th><th>Tanda Tangan</th></tr></thead>
        <tbody>
        @foreach ($kegiatan->tugasTim as $t)
            <tr><td style="text-align:center">{{ $loop->iteration }}</td><td>{{ $t->anggota->nim ?? '—' }}</td><td>{{ $t->anggota->nama }}</td><td>{{ $t->tugas }}</td><td class="ttd"></td></tr>
        @endforeach
        </tbody>
    </table>
    <div class="footer">
        <div class="footer-box"><p>Dosen Pembimbing Lapangan</p><div class="line"></div><p>{{ $nama_dpl }}<br>NIDN. {{ $nidn_dpl }}</p></div>
        <div class="footer-box"><p>PIC Kegiatan</p><div class="line"></div><p>{{ $kegiatan->pic?->nama ?? '' }}<br>NIM. {{ $kegiatan->pic?->nim ?? '' }}</p></div>
    </div>
</body>
</html>
