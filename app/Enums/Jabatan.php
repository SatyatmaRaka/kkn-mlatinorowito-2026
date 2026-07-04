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

    /** Sekretaris mengelola CMS website. */
    public function dapatKelolaCms(): bool
    {
        return $this === self::Sekretaris;
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
     * Ringkasan tugas divisi untuk dasbor anggota (Humas, PDD, Perlengkapan, dll.).
     *
     * @return array{judul: string, deskripsi: string, tugas: list<string>, ikon: string, warna: string}
     */
    public function infoDasbor(): array
    {
        return match ($this) {
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
                'deskripsi' => 'Mengelola administrasi, arsip surat, dan konten website KKN.',
                'tugas' => ['Arsip surat masuk & keluar', 'Kelola data anggota & kegiatan publik', 'Update informasi website'],
                'ikon' => 'bi-envelope-paper-fill',
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
    }

    /** @return array{judul: string, deskripsi: string, tugas: list<string>, ikon: string, warna: string} */
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
        ];
    }
}
