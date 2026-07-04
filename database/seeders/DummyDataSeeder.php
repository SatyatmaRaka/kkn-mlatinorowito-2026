<?php

namespace Database\Seeders;

use App\Layanan\LayananTokenAbsensi;
use App\Models\Absensi;
use App\Models\Anggota;
use App\Models\Keuangan;
use App\Models\Logbook;
use App\Models\ProgramKerja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Data dummy lengkap untuk pengujian lokal — logbook, absensi, keuangan, dll.
 * Aman dijalankan setelah DatabaseSeeder: php artisan db:seed --class=DummyDataSeeder
 */
class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->bersihkanDataOperasional();
        $this->seedAnggotaDetail();
        $this->seedProgramKerja();
        $this->seedLogbook();
        $this->seedAbsensi();
        $this->seedKeuangan();
        LayananTokenAbsensi::getActive();

        if ($this->command) {
            $this->command->info('Data dummy operasional berhasil diisi.');
            $this->command->line('  Logbook    : '.Logbook::count());
            $this->command->line('  Absensi    : '.Absensi::count());
            $this->command->line('  Keuangan   : '.Keuangan::count());
        }
    }

    private function bersihkanDataOperasional(): void
    {
        Logbook::query()->delete();
        Absensi::query()->delete();
        Keuangan::query()->delete();
        DB::table('notifications')->delete();
    }

    private function seedAnggotaDetail(): void
    {
        $nim = [
            1 => '2021001001', 2 => '2021001002', 3 => '2021001003', 4 => '2021001004',
            5 => '2021001005', 6 => '2021001006', 7 => '2021001007', 8 => '2021001008',
            9 => '2021001009', 10 => '2021001010', 11 => '2021001011',
        ];

        foreach (Anggota::query()->orderBy('urutan')->get() as $anggota) {
            $anggota->update([
                'nim' => $nim[$anggota->urutan] ?? null,
                'deskripsi' => 'Anggota KKN Mlatinorowito 2026 — divisi '.$anggota->jabatan.'.',
            ]);
        }
    }

    private function seedProgramKerja(): void
    {
        $data = [
            [
                'urutan' => 1,
                'judul' => 'Posyandu Balita & Lansia',
                'tema' => 'Kesehatan Masyarakat',
                'deskripsi' => 'Pendampingan posyandu, edukasi gizi, dan pemeriksaan kesehatan dasar warga.',
                'icon' => 'bi-heart-pulse',
                'pic' => 'PDD',
                'status' => 'Aktif',
            ],
            [
                'urutan' => 2,
                'judul' => 'Literasi Digital Warga',
                'tema' => 'Pemberdayaan Masyarakat',
                'deskripsi' => 'Pelatihan penggunaan smartphone, e-banking, dan literasi media sosial untuk RT/RW.',
                'icon' => 'bi-phone',
                'pic' => 'Humas',
                'status' => 'Aktif',
            ],
            [
                'urutan' => 3,
                'judul' => 'Bank Sampah Kelurahan',
                'tema' => 'Lingkungan Hidup',
                'deskripsi' => 'Sosialisasi pemilahan sampah dan pilot bank sampah di tingkat RW.',
                'icon' => 'bi-recycle',
                'pic' => 'PDD',
                'status' => 'Coming Soon',
            ],
            [
                'urutan' => 4,
                'judul' => 'Desa Wisata Mlatibaru',
                'tema' => 'Ekonomi Kreatif',
                'deskripsi' => 'Mapping potensi wisata, branding UMKM lokal, dan konten promosi digital.',
                'icon' => 'bi-camera',
                'pic' => 'Humas',
                'status' => 'Coming Soon',
            ],
            [
                'urutan' => 5,
                'judul' => 'Pemetaan UMKM & Pelatihan',
                'tema' => 'UMKM',
                'deskripsi' => 'Inventarisasi UMKM di kelurahan dan pendampingan pencatatan keuangan sederhana.',
                'icon' => 'bi-shop',
                'pic' => 'PDD',
                'status' => 'Aktif',
            ],
        ];

        foreach ($data as $item) {
            ProgramKerja::updateOrCreate(['urutan' => $item['urutan']], $item);
        }
    }

    private function seedLogbook(): void
    {
        $koordinator = User::query()->where('role', 'koordinator')->first()
            ?? User::query()->whereHas('anggota', fn ($q) => $q->where('jabatan', 'Koordinator Desa'))->first();

        $entries = [
            ['hari' => 0, 'judul' => 'Persiapan alat pelatihan literasi digital', 'status' => Logbook::STATUS_APPROVED],
            ['hari' => 0, 'judul' => 'Koordinasi jadwal posyandu dengan kader', 'status' => Logbook::STATUS_SUBMITTED],
            ['hari' => 1, 'judul' => 'Survei UMKM di RW 03', 'status' => Logbook::STATUS_APPROVED],
            ['hari' => 1, 'judul' => 'Input data hasil FGD RT/RW', 'status' => Logbook::STATUS_SUBMITTED],
            ['hari' => 2, 'judul' => 'Dokumentasi kegiatan humas', 'status' => Logbook::STATUS_DRAFT],
            ['hari' => 2, 'judul' => 'Pembuatan materi sosialisasi bank sampah', 'status' => Logbook::STATUS_REJECTED],
            ['hari' => 3, 'judul' => 'Rapat koordinasi internal kelompok', 'status' => Logbook::STATUS_APPROVED],
        ];

        foreach (User::query()->whereNotNull('anggota_id')->get() as $index => $user) {
            $pick = $entries[$index % count($entries)];
            $tanggal = now()->subDays($pick['hari']);

            Logbook::create([
                'user_id' => $user->id,
                'anggota_id' => $user->anggota_id,
                'tanggal' => $tanggal,
                'judul' => $pick['judul'],
                'deskripsi' => 'Kegiatan harian divisi '.($user->anggota?->jabatan ?? 'KKN').' di Kelurahan Mlatinorowito. Meliputi koordinasi lapangan, dokumentasi, dan evaluasi singkat hasil kegiatan.',
                'jam_mulai' => '08:00',
                'jam_selesai' => '12:00',
                'lokasi' => 'Kelurahan Mlatinorowito, Kudus',
                'status' => $pick['status'],
                'catatan_reviewer' => $pick['status'] === Logbook::STATUS_REJECTED ? 'Lengkapi dokumentasi foto kegiatan.' : null,
                'reviewed_by' => in_array($pick['status'], [Logbook::STATUS_APPROVED, Logbook::STATUS_REJECTED], true) ? $koordinator?->id : null,
                'reviewed_at' => in_array($pick['status'], [Logbook::STATUS_APPROVED, Logbook::STATUS_REJECTED], true) ? now()->subHours(2) : null,
            ]);

            if ($index % 2 === 0) {
                Logbook::create([
                    'user_id' => $user->id,
                    'anggota_id' => $user->anggota_id,
                    'tanggal' => now()->subDays(4),
                    'judul' => 'Arsip kegiatan minggu lalu — '.$user->anggota?->jabatan,
                    'deskripsi' => 'Logbook arsip kegiatan pekan pertama KKN.',
                    'jam_mulai' => '13:00',
                    'jam_selesai' => '16:00',
                    'lokasi' => 'Posko KKN Mlatinorowito',
                    'status' => Logbook::STATUS_APPROVED,
                    'reviewed_by' => $koordinator?->id,
                    'reviewed_at' => now()->subDays(3),
                ]);
            }
        }
    }

    private function seedAbsensi(): void
    {
        $users = User::query()->whereNotNull('anggota_id')->get();

        for ($day = 6; $day >= 0; $day--) {
            $tanggal = now()->subDays($day)->toDateString();

            foreach ($users as $index => $user) {
                if ($day === 0 && $index >= $users->count() - 2) {
                    continue;
                }

                if ($day === 1 && $index === 3) {
                    continue;
                }

                Absensi::create([
                    'user_id' => $user->id,
                    'anggota_id' => $user->anggota_id,
                    'tanggal' => $tanggal,
                    'check_in_at' => Carbon::parse($tanggal.' 07:'.sprintf('%02d', 10 + ($index % 40))),
                    'metode' => 'qr',
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Seeder/DummyData',
                ]);
            }
        }
    }

    private function seedKeuangan(): void
    {
        $bendahara = User::query()->whereHas('anggota', fn ($q) => $q->where('jabatan', 'Bendahara'))->first()
            ?? User::query()->where('role', 'koordinator')->first();

        $transaksi = [
            ['tanggal' => -10, 'jenis' => 'pemasukan', 'keterangan' => 'Dana awal operasional KKN dari fakultas', 'nominal' => 5_000_000],
            ['tanggal' => -8, 'jenis' => 'pengeluaran', 'keterangan' => 'Pembelian spanduk dan banner program kerja', 'nominal' => 450_000],
            ['tanggal' => -7, 'jenis' => 'pengeluaran', 'keterangan' => 'ATK dan alat tulis posko KKN', 'nominal' => 275_000],
            ['tanggal' => -5, 'jenis' => 'pengeluaran', 'keterangan' => 'Konsumsi rapat koordinasi dengan kelurahan', 'nominal' => 320_000],
            ['tanggal' => -4, 'jenis' => 'pengeluaran', 'keterangan' => 'Transport survei lapangan RT/RW', 'nominal' => 150_000],
            ['tanggal' => -3, 'jenis' => 'pengeluaran', 'keterangan' => 'Snack pelatihan literasi digital', 'nominal' => 380_000],
            ['tanggal' => -2, 'jenis' => 'pengeluaran', 'keterangan' => 'Cetak materi sosialisasi posyandu', 'nominal' => 125_000],
            ['tanggal' => -1, 'jenis' => 'pengeluaran', 'keterangan' => 'Belanja logistik kegiatan posyandu', 'nominal' => 290_000],
            ['tanggal' => 0, 'jenis' => 'pemasukan', 'keterangan' => 'Sumbangan warga untuk kegiatan bank sampah (RW 05)', 'nominal' => 500_000],
        ];

        foreach ($transaksi as $row) {
            Keuangan::create([
                'user_id' => $bendahara?->id,
                'diubah_oleh' => $bendahara?->id,
                'tanggal' => now()->addDays($row['tanggal'])->toDateString(),
                'jenis' => $row['jenis'],
                'keterangan' => $row['keterangan'],
                'nominal' => $row['nominal'],
            ]);
        }
    }
}
