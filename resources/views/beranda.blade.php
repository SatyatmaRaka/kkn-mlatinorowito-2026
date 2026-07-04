<x-layouts.public>
    <x-slot:title>{{ $pengaturan['nama_kelompok'] ?? 'KKN Mlatinorowito 2026' }} — UMK</x-slot:title>
    <x-slot:description>{{ $pengaturan['tagline'] ?? 'Website resmi KKN UMK Kelompok Desa Mlatinorowito 2026. Program kerja, anggota tim, dan informasi kelompok KKN.' }}</x-slot:description>
    <x-slot:image>{{ asset('images/logo.png') }}</x-slot:image>

    @include('beranda._styles')
    @include('beranda.hero')
    @include('beranda.tentang')
    @include('beranda.anggota')
    @include('beranda.proker')
    @include('beranda.kontak')
</x-layouts.public>
