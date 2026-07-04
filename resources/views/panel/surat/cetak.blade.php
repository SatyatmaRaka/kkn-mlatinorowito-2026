<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Keluar — {{ $surat->perihal }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.45; color: #000; max-width: 210mm; margin: 0 auto; padding: 18mm 16mm; }
        p { margin: 0 0 10px; }
        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 4px; }

        .kop-table { margin-bottom: 6px; }
        .kop-logo { width: 80px; vertical-align: middle; }
        .kop-logo img { display: block; }
        .kop-logo-kkn img { margin-left: auto; }
        .kop-teks { text-align: center; vertical-align: middle; padding: 0 8px; }
        .kop-judul { font-size: 13pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .kop-univ { font-size: 12pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .kop-periode { font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; }
        .kop-kontak { font-size: 9pt; line-height: 1.35; }

        .garis-kop { border-bottom: 2px solid #000; margin-bottom: 18px; }

        .meta-table { margin-bottom: 20px; }
        .meta-label { width: 70px; }
        .meta-sep { width: 8px; }
        .meta-kanan { padding-top: 2px; }

        .penerima { margin-bottom: 16px; }
        .salam { margin-bottom: 14px; }
        .isi { text-align: justify; margin-bottom: 10px; }
        .penutup { text-align: justify; margin-bottom: 24px; }

        .ttd-mengetahui { text-align: center; margin-top: 8px; margin-bottom: 2px; }
        .ttd-organisasi { text-align: center; font-weight: bold; margin-bottom: 20px; }
        .ttd-table { margin-bottom: 28px; }
        .ttd-jabatan { margin-bottom: 4px; }
        .ttd-ruang { height: 64px; }
        .ttd-nama { margin-bottom: 2px; }
        .ttd-nim { font-size: 11pt; }

        .ttd-menyetujui { text-align: center; margin-bottom: 2px; }
        .ttd-dpl-jabatan { text-align: center; margin-bottom: 4px; }
        .ttd-dpl-ruang { height: 64px; }
        .ttd-dpl-nama { text-align: center; }

        @media print {
            body { padding: 15mm; }
            .no-print { display: none !important; }
        }
        .toolbar { position: fixed; top: 12px; right: 12px; z-index: 10; }
        .toolbar button { padding: 8px 16px; cursor: pointer; border: 1px solid #ccc; background: #fff; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="toolbar no-print">
        <button type="button" onclick="window.print()">Cetak</button>
    </div>

    @include('panel.surat._surat-resmi', [
        'surat' => $surat,
        'pengaturan' => $pengaturan,
        'penandatangan' => $penandatangan,
        'logoKkn' => $logoKkn,
        'logoUmk' => $logoUmk,
        'paragrafIsi' => $paragrafIsi ?? null,
    ])
</body>
</html>
