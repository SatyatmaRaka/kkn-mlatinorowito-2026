<?php

namespace App\Enums;

/**
 * Jabatan organisasi kelompok KKN.
 * Dipakai validasi form anggota & pengecekan izin CMS/keuangan.
 */
enum Jabatan: string
{
    case KoordinatorDesa = 'Koordinator Desa';
    case WakilKoordinator = 'Wakil Koordinator';
    case PDD = 'PDD';
    case Perlengkapan = 'Perlengkapan';
    case Humas = 'Humas';
    case Sekretaris = 'Sekretaris';
    case Bendahara = 'Bendahara';

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /** @return list<string> */
    public static function pimpinanValues(): array
    {
        return [
            self::KoordinatorDesa->value,
            self::WakilKoordinator->value,
        ];
    }

    public function isPimpinan(): bool
    {
        return in_array($this, [self::KoordinatorDesa, self::WakilKoordinator], true);
    }

    /** @deprecated Tidak dipakai — CMS hanya admin. */
    public function dapatKelolaCms(): bool
    {
        return false;
    }

    /** Bendahara mengelola keuangan (selain admin/koordinator). */
    public function dapatKelolaKeuangan(): bool
    {
        return $this === self::Bendahara;
    }

    public static function tryFromValue(?string $value): ?self
    {
        return $value === null ? null : self::tryFrom($value);
    }

    /**
     * Daftar modul panel operasional untuk navigasi & dasbor.
     *
     * @return array<string, array{label: string, route: string, pola: string, ikon: string}>
     */
    public static function registryModul(): array
    {
        return [
            'catatan-harian' => [
                'label' => 'Logbook',
                'route' => 'panel.catatan-harian.index',
                'pola' => 'panel.catatan-harian.*',
                'ikon' => 'bi-journal-text',
            ],
            'absensi' => [
                'label' => 'Absensi',
                'route' => 'panel.absensi.riwayat',
                'pola' => 'panel.absensi.riwayat',
                'ikon' => 'bi-clock-history',
            ],
            'buku-tamu' => [
                'label' => 'Buku Tamu',
                'route' => 'panel.buku-tamu.index',
                'pola' => 'panel.buku-tamu.*',
                'ikon' => 'bi-book',
            ],
            'kegiatan-pelaksanaan' => [
                'label' => 'Kegiatan Pelaksanaan',
                'route' => 'panel.kegiatan-pelaksanaan.index',
                'pola' => 'panel.kegiatan-pelaksanaan.*',
                'ikon' => 'bi-calendar-event',
            ],
            'observasi-lapangan' => [
                'label' => 'Observasi Lapangan',
                'route' => 'panel.observasi-lapangan.index',
                'pola' => 'panel.observasi-lapangan.*',
                'ikon' => 'bi-binoculars',
            ],
        ];
    }

    /** @return list<string> */
    public static function modulWajibHarian(): array
    {
        return ['catatan-harian', 'absensi'];
    }

    /** @return list<string> */
    public static function semuaModulOperasional(): array
    {
        return ['catatan-harian', 'absensi', 'buku-tamu', 'kegiatan-pelaksanaan', 'observasi-lapangan'];
    }

    /** Modul yang dikerjakan bersama seluruh tim (ditampilkan jika bukan modul utama divisi). */
    /** @return list<string> */
    public static function modulKolaborasiTim(): array
    {
        return ['observasi-lapangan', 'kegiatan-pelaksanaan'];
    }

    /** Modul utama yang menjadi tanggung jawab divisi ini. */
    /** @return list<string> */
    public function kunciModulDivisi(): array
    {
        return match ($this) {
            self::Humas => ['buku-tamu', 'kegiatan-pelaksanaan'],
            self::PDD => ['kegiatan-pelaksanaan', 'observasi-lapangan'],
            self::Sekretaris => ['buku-tamu'],
            self::Perlengkapan, self::Bendahara => [],
            self::KoordinatorDesa, self::WakilKoordinator => self::semuaModulOperasional(),
        };
    }

    /**
     * Tombol aksi cepat di dasbor anggota per divisi.
     *
     * @return list<array{label: string, route: string, ikon: string, varian: string}>
     */
    public function aksiCepatDasbor(): array
    {
        return match ($this) {
            self::Humas => [
                ['label' => 'Catat Tamu', 'route' => 'panel.buku-tamu.create', 'ikon' => 'bi-book', 'varian' => 'primary'],
                ['label' => 'Kegiatan Pelaksanaan', 'route' => 'panel.kegiatan-pelaksanaan.index', 'ikon' => 'bi-calendar-event', 'varian' => 'outline-primary'],
                ['label' => 'Tulis Logbook', 'route' => 'panel.catatan-harian.create', 'ikon' => 'bi-journal-plus', 'varian' => 'outline-primary'],
                ['label' => 'Absensi Posko', 'route' => 'absensi.scan', 'ikon' => 'bi-qr-code-scan', 'varian' => 'outline-secondary'],
            ],
            self::PDD => [
                ['label' => 'Kegiatan Pelaksanaan', 'route' => 'panel.kegiatan-pelaksanaan.create', 'ikon' => 'bi-calendar-plus', 'varian' => 'primary'],
                ['label' => 'Observasi Lapangan', 'route' => 'panel.observasi-lapangan.index', 'ikon' => 'bi-binoculars', 'varian' => 'outline-primary'],
                ['label' => 'Tulis Logbook', 'route' => 'panel.catatan-harian.create', 'ikon' => 'bi-journal-plus', 'varian' => 'outline-primary'],
                ['label' => 'Absensi Posko', 'route' => 'absensi.scan', 'ikon' => 'bi-qr-code-scan', 'varian' => 'outline-secondary'],
            ],
            self::Sekretaris => [
                ['label' => 'Catat Tamu', 'route' => 'panel.buku-tamu.create', 'ikon' => 'bi-book', 'varian' => 'primary'],
                ['label' => 'Tulis Logbook', 'route' => 'panel.catatan-harian.create', 'ikon' => 'bi-journal-plus', 'varian' => 'outline-primary'],
                ['label' => 'Absensi Posko', 'route' => 'absensi.scan', 'ikon' => 'bi-qr-code-scan', 'varian' => 'outline-primary'],
                ['label' => 'Riwayat Absensi', 'route' => 'panel.absensi.riwayat', 'ikon' => 'bi-clock-history', 'varian' => 'outline-secondary'],
            ],
            self::Perlengkapan => [
                ['label' => 'Tulis Logbook', 'route' => 'panel.catatan-harian.create', 'ikon' => 'bi-journal-plus', 'varian' => 'primary'],
                ['label' => 'Absensi Posko', 'route' => 'absensi.scan', 'ikon' => 'bi-qr-code-scan', 'varian' => 'outline-primary'],
                ['label' => 'Riwayat Absensi', 'route' => 'panel.absensi.riwayat', 'ikon' => 'bi-clock-history', 'varian' => 'outline-secondary'],
            ],
            self::Bendahara => [
                ['label' => 'Catat Transaksi', 'route' => 'panel.keuangan.create', 'ikon' => 'bi-plus-circle', 'varian' => 'primary'],
                ['label' => 'Tulis Logbook', 'route' => 'panel.catatan-harian.create', 'ikon' => 'bi-journal-plus', 'varian' => 'outline-primary'],
                ['label' => 'Absensi Posko', 'route' => 'absensi.scan', 'ikon' => 'bi-qr-code-scan', 'varian' => 'outline-secondary'],
            ],
            self::KoordinatorDesa, self::WakilKoordinator => [],
        };
    }

    /**
     * Ringkasan tugas divisi untuk dasbor anggota (Humas, PDD, Perlengkapan, dll.).
     *
     * @return array{judul: string, deskripsi: string, tugas: list<string>, ikon: string, warna: string, modul: list<string>, aksi_cepat: list<array{label: string, route: string, ikon: string, varian: string}>}
     */
    public function infoDasbor(): array
    {
        $info = match ($this) {
            self::KoordinatorDesa => [
                'judul' => 'Koordinator Desa',
                'deskripsi' => 'Memimpin operasional KKN dan koordinasi seluruh divisi.',
                'tugas' => ['Review logbook anggota', 'Pantau rekap absensi harian', 'Koordinasi program kerja'],
                'ikon' => 'bi-star-fill',
                'warna' => 'primary',
            ],
            self::WakilKoordinator => [
                'judul' => 'Wakil Koordinator',
                'deskripsi' => 'Mendampingi koordinator dalam pengawasan kegiatan harian KKN.',
                'tugas' => ['Backup review logbook', 'Pantau kehadiran anggota', 'Koordinasi lapangan dengan koordinator'],
                'ikon' => 'bi-person-check-fill',
                'warna' => 'primary',
            ],
            self::Sekretaris => [
                'judul' => 'Sekretaris',
                'deskripsi' => 'Mendukung administrasi dan dokumentasi kegiatan kelompok KKN.',
                'tugas' => ['Catat logbook harian', 'Absensi di posko sesuai jadwal', 'Dukung administrasi kelompok'],
                'ikon' => 'bi-journal-text',
                'warna' => 'info',
            ],
            self::Bendahara => [
                'judul' => 'Bendahara',
                'deskripsi' => 'Mencatat dan melaporkan pemasukan serta pengeluaran dana KKN.',
                'tugas' => ['Catat transaksi keuangan', 'Pantau saldo kas kelompok', 'Siapkan laporan keuangan'],
                'ikon' => 'bi-wallet2',
                'warna' => 'success',
            ],
            self::Humas => [
                'judul' => 'Humas',
                'deskripsi' => 'Menjalin hubungan dengan masyarakat dan publikasi kegiatan KKN.',
                'tugas' => ['Dokumentasikan kegiatan untuk publikasi', 'Koordinasi dengan warga & stakeholders', 'Catat aktivitas humas di logbook harian'],
                'ikon' => 'bi-megaphone-fill',
                'warna' => 'warning',
            ],
            self::PDD => [
                'judul' => 'PDD',
                'deskripsi' => 'Program Desa dan Dana — perencanaan serta pelaksanaan program kerja.',
                'tugas' => ['Pantau progres program kerja', 'Dokumentasi kegiatan PDD di logbook', 'Koordinasi pelaksanaan program di lapangan'],
                'ikon' => 'bi-kanban-fill',
                'warna' => 'success',
            ],
            self::Perlengkapan => [
                'judul' => 'Perlengkapan',
                'deskripsi' => 'Mengelola inventaris dan kebutuhan logistik kegiatan KKN.',
                'tugas' => ['Catat penggunaan & kondisi perlengkapan', 'Koordinasi kebutuhan logistik kegiatan', 'Laporkan via logbook harian'],
                'ikon' => 'bi-box-seam-fill',
                'warna' => 'secondary',
            ],
        };

        return array_merge($info, [
            'modul' => $this->kunciModulDivisi(),
            'aksi_cepat' => $this->aksiCepatDasbor(),
        ]);
    }

    /** @return array{judul: string, deskripsi: string, tugas: list<string>, ikon: string, warna: string, modul: list<string>, aksi_cepat: list<array{label: string, route: string, ikon: string, varian: string}>} */
    public static function infoDasborUntuk(?string $jabatan): array
    {
        $enum = self::tryFromValue($jabatan);

        if ($enum) {
            return $enum->infoDasbor();
        }

        return [
            'judul' => 'Anggota KKN',
            'deskripsi' => 'Melaksanakan kegiatan KKN dan melaporkan aktivitas harian.',
            'tugas' => ['Isi logbook harian', 'Absensi di posko sesuai jadwal', 'Ikuti program kerja kelompok'],
            'ikon' => 'bi-person-fill',
            'warna' => 'primary',
            'modul' => [],
            'aksi_cepat' => [
                ['label' => 'Tulis Logbook', 'route' => 'panel.catatan-harian.create', 'ikon' => 'bi-journal-plus', 'varian' => 'primary'],
                ['label' => 'Absensi Posko', 'route' => 'absensi.scan', 'ikon' => 'bi-qr-code-scan', 'varian' => 'outline-primary'],
                ['label' => 'Riwayat Absensi', 'route' => 'panel.absensi.riwayat', 'ikon' => 'bi-clock-history', 'varian' => 'outline-secondary'],
            ],
        ];
    }

    /**
     * @param  list<string>  $kunci
     * @return list<array{kunci: string, label: string, route: string, pola: string, ikon: string}>
     */
    public static function detailModulUntuk(array $kunci): array
    {
        $registry = self::registryModul();

        return array_values(array_filter(array_map(
            fn (string $key) => isset($registry[$key])
                ? array_merge(['kunci' => $key], $registry[$key])
                : null,
            $kunci
        )));
    }
}
