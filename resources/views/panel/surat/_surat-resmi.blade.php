@php
    $namaKelompok = strtoupper($pengaturan['nama_kelompok'] ?? 'KKN Mlatinorowito 2026');
    $periode = strtoupper($pengaturan['periode_kkn'] ?? 'Juli - Agustus 2026');
    $alamat = $pengaturan['alamat'] ?? 'Kelurahan Mlatinorowito, Kudus';
    $email = $pengaturan['email'] ?? '';
    $instagram = $pengaturan['instagram'] ?? '';
    $kontak = collect([
        'Sekretariat: '.$alamat,
        $email !== '' ? 'Email: '.$email : null,
        $instagram !== '' ? 'Instagram: '.$instagram : null,
    ])->filter()->implode(' · ');
    $paragraf = $paragrafIsi ?? \App\Penunjang\PenandatanganSurat::paragrafIsi($surat->keterangan);
@endphp

<table class="kop-table" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="kop-logo kop-logo-umk" valign="middle">
            @if ($logoUmk ?? null)
                <img src="{{ $logoUmk['src'] }}" width="{{ $logoUmk['width'] }}" height="{{ $logoUmk['height'] }}" alt="Logo UMK">
            @endif
        </td>
        <td class="kop-teks">
            <div class="kop-judul">{{ $namaKelompok }}</div>
            <div class="kop-univ">UNIVERSITAS MURIA KUDUS</div>
            <div class="kop-periode">PERIODE {{ $periode }}</div>
            <div class="kop-kontak">{{ $kontak }}</div>
        </td>
        <td class="kop-logo kop-logo-kkn" valign="middle" align="right">
            @if ($logoKkn ?? null)
                <img src="{{ $logoKkn['src'] }}" width="{{ $logoKkn['width'] }}" height="{{ $logoKkn['height'] }}" alt="Logo KKN">
            @endif
        </td>
    </tr>
</table>

<div class="garis-kop"></div>

<table class="meta-table" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="meta-kiri" width="55%" valign="top">
            <table cellpadding="0" cellspacing="0">
                @if ($surat->nomor_surat)
                    <tr>
                        <td class="meta-label">No</td>
                        <td class="meta-sep">:</td>
                        <td>{{ $surat->nomor_surat }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="meta-label">Lampiran</td>
                    <td class="meta-sep">:</td>
                    <td>—</td>
                </tr>
                <tr>
                    <td class="meta-label">Hal</td>
                    <td class="meta-sep">:</td>
                    <td><strong>{{ $surat->perihal }}</strong></td>
                </tr>
            </table>
        </td>
        <td class="meta-kanan" width="45%" valign="top" align="right">
            Kudus, {{ $surat->tanggal->locale('id')->translatedFormat('d F Y') }}
        </td>
    </tr>
</table>

<div class="penerima">
    <p class="mb-1">Kepada Yth.</p>
    <p class="mb-1"><strong>{{ $surat->teksPenerima() }}</strong></p>
    <p class="mb-0">Di Tempat</p>
</div>

<p class="salam">Dengan Hormat,</p>

@foreach ($paragraf as $teks)
    <p class="isi">{{ $teks }}</p>
@endforeach

<p class="penutup">Demikian surat ini kami buat untuk dapat dipergunakan sebagaimana mestinya. Atas perhatian dan kerja sama Bapak/Ibu, kami ucapkan terima kasih.</p>

<div class="ttd-mengetahui">Mengetahui,</div>
<div class="ttd-organisasi">{{ $pengaturan['nama_kelompok'] ?? 'KKN Mlatinorowito 2026' }}</div>

<table class="ttd-table" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td width="50%" valign="top" align="center">
            <div class="ttd-jabatan">{{ $penandatangan['koordinator']['jabatan'] }}</div>
            <div class="ttd-ruang"></div>
            <div class="ttd-nama"><strong><u>{{ $penandatangan['koordinator']['nama'] }}</u></strong></div>
            @if (! empty($penandatangan['koordinator']['nim']))
                <div class="ttd-nim">NIM : {{ $penandatangan['koordinator']['nim'] }}</div>
            @endif
        </td>
        <td width="50%" valign="top" align="center">
            <div class="ttd-jabatan">{{ $penandatangan['sekretaris']['jabatan'] }}</div>
            <div class="ttd-ruang"></div>
            <div class="ttd-nama"><strong><u>{{ $penandatangan['sekretaris']['nama'] }}</u></strong></div>
            @if (! empty($penandatangan['sekretaris']['nim']))
                <div class="ttd-nim">NIM : {{ $penandatangan['sekretaris']['nim'] }}</div>
            @endif
        </td>
    </tr>
</table>

<div class="ttd-menyetujui">Menyetujui</div>
<div class="ttd-dpl-jabatan">{{ $penandatangan['dpl']['jabatan'] }}</div>
<div class="ttd-dpl-ruang"></div>
<div class="ttd-dpl-nama"><strong><u>{{ $penandatangan['dpl']['nama'] }}</u></strong></div>
