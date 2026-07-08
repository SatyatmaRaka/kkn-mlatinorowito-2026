<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Operasional Program Kerja</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 1.5rem; font-size: 12px; color: #111; }
        h1 { text-align: center; font-size: 1rem; text-transform: uppercase; margin: 0; }
        .sub { text-align: center; font-weight: bold; margin: 0.25rem 0 1rem; }
        .section-title { font-weight: bold; margin: 1rem 0 0.5rem; text-transform: uppercase; font-size: 11px; }
        table.detail { width: 100%; border-collapse: collapse; margin-bottom: 0.5rem; }
        table.detail td { padding: 0.25rem 0.5rem 0.25rem 0; vertical-align: top; }
        table.detail td.label { width: 28%; font-weight: bold; white-space: nowrap; }
        table.detail td.sep { width: 1%; }
        .indent { padding-left: 1.5rem; }
        .footer { display: flex; justify-content: space-between; margin-top: 2.5rem; }
        .footer-box { width: 45%; text-align: center; }
        .footer-box .line { border-bottom: 1px solid #333; margin: 3rem 1rem 0.5rem; min-height: 1px; }
        .footer-center { text-align: center; margin-top: 2rem; }
        .footer-center .line { border-bottom: 1px solid #333; margin: 3rem auto 0.5rem; width: 60%; min-height: 1px; }
        .stempel { min-height: 60px; margin: 0.5rem 0; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:1rem">
        <button onclick="window.print()">Cetak</button>
        <a href="{{ route('panel.kegiatan-pelaksanaan.show', $kegiatan) }}">Kembali</a>
    </div>

    <h1>Operasional Program Kerja</h1>
    <p class="sub">Desa {{ $desa }} / Kordes {{ $kordes_nama }} (NIM. {{ $kordes_nim }})</p>
    <p class="sub" style="margin-top:0">Kec. {{ $kecamatan }} / Kab. {{ $kabupaten }} / DPL {{ $nama_dpl }} (NIDN. {{ $nidn_dpl }})</p>

    <div class="section-title">Pelaksanaan</div>
    <table class="detail">
        <tr><td class="label">Tema Kegiatan</td><td class="sep">:</td><td>{{ $kegiatan->tema_kegiatan ?? '—' }}</td></tr>
        <tr><td class="label">Nama Kegiatan</td><td class="sep">:</td><td>{{ $kegiatan->nama_kegiatan }}</td></tr>
        <tr><td class="label">Waktu pelaksanaan</td><td class="sep">:</td><td>{{ $kegiatan->tanggal->locale('id')->translatedFormat('l, d F Y') }}, {{ substr($kegiatan->waktu_mulai, 0, 5) }} – {{ substr($kegiatan->waktu_selesai, 0, 5) }} WIB</td></tr>
        <tr><td class="label">Tempat pelaksanaan</td><td class="sep">:</td><td>{{ $kegiatan->tempat }}</td></tr>
        <tr><td class="label">Latar Belakang</td><td class="sep">:</td><td>{{ $kegiatan->latar_belakang ?? '—' }}</td></tr>
    </table>

    <table class="detail">
        <tr><td class="label">Kondisi yang mendukung</td><td class="sep">:</td><td>{{ $kegiatan->kondisi_mendukung ?? '—' }}</td></tr>
    </table>

    <table class="detail">
        <tr><td class="label">Manfaat/Tujuan</td><td class="sep">:</td><td>{{ $kegiatan->manfaat_tujuan ?? '—' }}</td></tr>
    </table>

    <table class="detail">
        <tr><td class="label">Sasaran/Peserta</td><td class="sep">:</td><td>{{ $kegiatan->pesertaMasyarakat->pluck('nama')->join(', ') ?: '—' }}</td></tr>
        <tr><td class="label">Jumlah Peserta</td><td class="sep">:</td><td>{{ $kegiatan->pesertaMasyarakat->count() }}</td></tr>
        <tr><td class="label">Besar Anggaran</td><td class="sep">:</td><td>Rp {{ number_format($kegiatan->total_anggaran, 0, ',', '.') }}</td></tr>
    </table>

    <table class="detail">
        <tr><td class="label">Sumber dana</td><td class="sep">:</td><td></td></tr>
        <tr><td class="label indent">a. Masyarakat</td><td class="sep">:</td><td>Rp {{ number_format($kegiatan->sumber_dana_masyarakat, 0, ',', '.') }}</td></tr>
        <tr><td class="label indent">b. Mahasiswa</td><td class="sep">:</td><td>Rp {{ number_format($kegiatan->sumber_dana_mahasiswa, 0, ',', '.') }}</td></tr>
        <tr><td class="label indent">c. Donatur/lainnya</td><td class="sep">:</td><td>Rp {{ number_format($kegiatan->sumber_dana_donatur, 0, ',', '.') }}@if($kegiatan->sumber_dana_donatur_keterangan) ({{ $kegiatan->sumber_dana_donatur_keterangan }})@endif</td></tr>
    </table>

    <table class="detail">
        <tr>
            <td class="label">Penanggung Jawab Kegiatan</td>
            <td class="sep">:</td>
            <td>
                @if ($kegiatan->tugasTim->isNotEmpty())
                    @foreach ($kegiatan->tugasTim as $t)
                        {{ $loop->iteration }}. {{ $t->anggota->nama }} — {{ $t->tugas }}@if(!$loop->last)<br>@endif
                    @endforeach
                @elseif ($kegiatan->pic)
                    1. {{ $kegiatan->pic->nama }}
                @else
                    —
                @endif
            </td>
        </tr>
    </table>

    <div class="footer">
        <div class="footer-box">
            <p>Dosen Pembimbing Lapangan</p>
            <div class="line"></div>
            <p>{{ $nama_dpl }}<br>NIDN. {{ $nidn_dpl }}</p>
        </div>
        <div class="footer-box">
            <p>Penanggung Jawab Kegiatan</p>
            <div class="line"></div>
            <p>{{ $kegiatan->pic?->nama ?? '' }}<br>NIM. {{ $kegiatan->pic?->nim ?? '' }}</p>
        </div>
    </div>

    <div class="footer-center">
        <p>Mengetahui,<br>Kepala Desa/Kelurahan</p>
        <div class="stempel"></div>
        <div class="line"></div>
        <p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</p>
    </div>
</body>
</html>
