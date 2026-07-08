<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Observasi Lapangan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 1.5rem; font-size: 12px; color: #111; }
        h1 { text-align: center; font-size: 1rem; text-transform: uppercase; margin: 0; }
        .sub { text-align: center; font-weight: bold; margin: 0.25rem 0 1rem; }
        .header-info { margin-bottom: 1rem; font-size: 11px; }
        .header-info p { margin: 0.15rem 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
        th, td { border: 1px solid #333; padding: 0.35rem; vertical-align: top; }
        th { background: #f5f5f5; text-align: center; font-size: 11px; }
        .narasi { margin: 1rem 0; }
        .narasi-title { font-weight: bold; margin-bottom: 0.25rem; }
        .narasi-body { min-height: 3rem; border: 1px solid #333; padding: 0.5rem; }
        .footer { margin-top: 2rem; }
        .footer-row { display: flex; justify-content: space-between; margin-bottom: 2rem; }
        .footer-box { width: 45%; text-align: center; }
        .footer-box .line { border-bottom: 1px solid #333; margin: 3rem 1rem 0.5rem; min-height: 1px; }
        .footer-center { text-align: center; margin-top: 1rem; }
        .footer-center .line { border-bottom: 1px solid #333; margin: 3rem auto 0.5rem; width: 50%; min-height: 1px; }
        .stempel { min-height: 70px; margin: 0.5rem 0; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:1rem">
        <button onclick="window.print()">Cetak</button>
        <a href="{{ route('panel.observasi-lapangan.index') }}">Kembali</a>
    </div>

    <h1>Observasi Lapangan</h1>
    <p class="sub">Desa {{ $desa }} Kecamatan {{ $kecamatan }} Kabupaten {{ $kabupaten }}</p>

    <div class="header-info">
        <p><strong>Kordes:</strong> {{ $kordes_nama }} (NIM. {{ $kordes_nim }}) &nbsp;|&nbsp; <strong>DPL:</strong> {{ $nama_dpl }} (NIDN. {{ $nidn_dpl }})</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th style="width:22%">Kegiatan/Kelembagaan masyarakat yang ada</th>
                <th style="width:8%">Aktifitas<br>(Ada/Tidak)</th>
                <th style="width:33%">Permasalahan</th>
                <th style="width:34%">Rencana Pemecahan Masalah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($observasi->items as $item)
                <tr>
                    <td style="text-align:center">{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_kelembagaan }}</td>
                    <td style="text-align:center">{{ $item->status === 'ada' ? 'Ada' : 'Tidak' }}</td>
                    <td>{{ $item->permasalahan ?? '' }}</td>
                    <td>{{ $item->rencana_pemecahan_masalah ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="narasi">
        <div class="narasi-title">Hasil identifikasi permasalahan utama yang ada di desa</div>
        <div class="narasi-body">{{ $observasi->ringkasan_permasalahan ?? '' }}</div>
    </div>

    <div class="narasi">
        <div class="narasi-title">Rencana, metode, dan TTG yang akan digunakan untuk pemecahan masalah utama</div>
        <div class="narasi-body">{{ $observasi->rencana_pemecahan ?? '' }}</div>
    </div>

    <div class="footer">
        <div class="footer-row">
            <div class="footer-box">
                <p>Dosen Pembimbing Lapangan</p>
                <div class="line"></div>
                <p>{{ $nama_dpl }}<br>NIDN. {{ $nidn_dpl }}</p>
            </div>
            <div class="footer-box">
                <p>Koordinator Desa</p>
                <div class="line"></div>
                <p>{{ $kordes_nama }}<br>NIM. {{ $kordes_nim }}</p>
            </div>
        </div>
        <div class="footer-center">
            <p>Kepala Desa/Lurah</p>
            <div class="stempel"></div>
            <div class="line"></div>
            <p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</p>
        </div>
    </div>
</body>
</html>
