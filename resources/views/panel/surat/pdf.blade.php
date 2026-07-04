<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 18mm 16mm; }
        body { font-family: DejaVu Serif, serif; font-size: 11pt; line-height: 1.45; color: #000; margin: 0; }
        p { margin: 0 0 10px; }
        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 4px; }

        .kop-table { margin-bottom: 6px; }
        .kop-logo { width: 80px; vertical-align: middle; }
        .kop-logo img { display: block; }
        .kop-logo-kkn img { margin-left: auto; }
        .kop-teks { text-align: center; vertical-align: middle; padding: 0 8px; }
        .kop-judul { font-size: 12pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .kop-univ { font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .kop-periode { font-size: 10pt; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; }
        .kop-kontak { font-size: 8.5pt; line-height: 1.35; }

        .garis-kop { border-bottom: 2px solid #000; margin-bottom: 16px; }

        .meta-table { margin-bottom: 18px; font-size: 11pt; }
        .meta-label { width: 62px; }
        .meta-sep { width: 8px; }
        .meta-kanan { padding-top: 2px; }

        .penerima { margin-bottom: 14px; }
        .salam { margin-bottom: 12px; }
        .isi { text-align: justify; margin-bottom: 10px; }
        .penutup { text-align: justify; margin-bottom: 22px; }

        .ttd-mengetahui { text-align: center; margin-top: 8px; margin-bottom: 2px; }
        .ttd-organisasi { text-align: center; font-weight: bold; margin-bottom: 18px; }
        .ttd-table { margin-bottom: 24px; }
        .ttd-jabatan { margin-bottom: 4px; }
        .ttd-ruang { height: 52px; }
        .ttd-nama { margin-bottom: 2px; }
        .ttd-nim { font-size: 10pt; }

        .ttd-menyetujui { text-align: center; margin-bottom: 2px; }
        .ttd-dpl-jabatan { text-align: center; margin-bottom: 4px; }
        .ttd-dpl-ruang { height: 52px; }
        .ttd-dpl-nama { text-align: center; }
    </style>
</head>
<body>
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
