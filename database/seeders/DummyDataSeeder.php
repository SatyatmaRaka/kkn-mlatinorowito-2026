<?php

namespace Database\Seeders;

use App\Enums\KategoriTujuanSurat;
use App\Layanan\LayananTokenAbsensi;
use App\Models\Absensi;
use App\Models\Anggota;
use App\Models\Galeri;
use App\Models\Keuangan;
use App\Models\Kegiatan;
use App\Models\Logbook;
use App\Models\ProgramKerja;
use App\Models\Surat;
use App\Models\User;
use App\Penunjang\GeneratorSuratKeluar;
use App\Penunjang\PenerimaSurat;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Data dummy lengkap untuk pengujian lokal — logbook, absensi, keuangan, surat, galeri, dll.
 * Aman dijalankan setelah DatabaseSeeder: php artisan db:seed --class=DummyDataSeeder
 */
class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->bersihkanDataOperasional();
        $this->seedAnggotaDetail();
        $this->seedProgramKerja();
        $this->seedKegiatan();
        $this->seedGaleri();
        $this->seedLogbook();
        $this->seedAbsensi();
        $this->seedKeuangan();
        $this->seedSurat();
        LayananTokenAbsensi::getActive();

        if ($this->command) {
            $this->command->info('Data dummy operasional berhasil diisi.');
            $this->command->line('  Logbook    : '.Logbook::count());
            $this->command->line('  Absensi    : '.Absensi::count());
            $this->command->line('  Keuangan   : '.Keuangan::count());
            $this->command->line('  Surat      : '.Surat::count());
            $this->command->line('  Galeri     : '.Galeri::count());
        }
    }

    private function bersihkanDataOperasional(): void
    {
        Surat::query()->each(function (Surat $surat) {
            if ($surat->lampiran) {
                Storage::disk('public')->delete($surat->lampiran);
            }
        });

        Galeri::query()->each(function (Galeri $item) {
            if ($item->foto && str_starts_with($item->foto, 'galeri/')) {
                Storage::disk('public')->delete($item->foto);
            }
        });

        Logbook::query()->delete();
        Absensi::query()->delete();
        Keuangan::query()->delete();
        Surat::query()->delete();
        Galeri::query()->delete();
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
                'status' => 'Berjalan',
            ],
            [
                'urutan' => 2,
                'judul' => 'Literasi Digital Warga',
                'tema' => 'Pemberdayaan Masyarakat',
                'deskripsi' => 'Pelatihan penggunaan smartphone, e-banking, dan literasi media sosial untuk RT/RW.',
                'icon' => 'bi-phone',
                'pic' => 'Humas',
                'status' => 'Berjalan',
            ],
            [
                'urutan' => 3,
                'judul' => 'Bank Sampah Kelurahan',
                'tema' => 'Lingkungan Hidup',
                'deskripsi' => 'Sosialisasi pemilahan sampah dan pilot bank sampah di tingkat RW.',
                'icon' => 'bi-recycle',
                'pic' => 'PDD',
                'status' => 'Perencanaan',
            ],
            [
                'urutan' => 4,
                'judul' => 'Desa Wisata Mlatibaru',
                'tema' => 'Ekonomi Kreatif',
                'deskripsi' => 'Mapping potensi wisata, branding UMKM lokal, dan konten promosi digital.',
                'icon' => 'bi-camera',
                'pic' => 'Humas',
                'status' => 'Perencanaan',
            ],
            [
                'urutan' => 5,
                'judul' => 'Pemetaan UMKM & Pelatihan',
                'tema' => 'UMKM',
                'deskripsi' => 'Inventarisasi UMKM di kelurahan dan pendampingan pencatatan keuangan sederhana.',
                'icon' => 'bi-shop',
                'pic' => 'PDD',
                'status' => 'Berjalan',
            ],
        ];

        foreach ($data as $item) {
            ProgramKerja::updateOrCreate(['urutan' => $item['urutan']], $item);
        }
    }

    private function seedKegiatan(): void
    {
        $data = [
            [
                'judul' => 'Pembukaan KKN & Sosialisasi ke Kelurahan',
                'tanggal' => now()->subDays(5),
                'deskripsi_singkat' => 'Perkenalan kelompok KKN kepada lurah dan perangkat kelurahan Mlatinorowito.',
                'konten' => '<p>Tim KKN UMK Mlatinorowito 2026 melakukan courtesy visit ke Kelurahan Mlatinorowito. Kegiatan diawali sambutan lurah, presentasi program kerja, dan kesepakatan jadwal koordinasi mingguan.</p>',
            ],
            [
                'judul' => 'Survei Awal & Focus Group Discussion RT/RW',
                'tanggal' => now()->subDays(3),
                'deskripsi_singkat' => 'Survei kebutuhan warga dan FGD bersama ketua RT/RW.',
                'konten' => '<p>Anggota KKN menyebar kuesioner sederhana dan mengadakan diskusi kelompok terfokus di tiga RW prioritas untuk memetakan permasalahan utama warga.</p>',
            ],
            [
                'judul' => 'Pelatihan Literasi Digital di Balai RW 05',
                'tanggal' => now()->subDay(),
                'deskripsi_singkat' => 'Pelatihan dasar smartphone dan keamanan digital untuk ibu-ibu PKK.',
                'konten' => '<p>Divisi Humas dan PDD mengadakan pelatihan literasi digital. Peserta belajar membuat akun email, menggunakan QRIS, dan mengenali modus penipuan online.</p>',
            ],
            [
                'judul' => 'Posyandu Bersama & Edukasi Gizi',
                'tanggal' => now(),
                'deskripsi_singkat' => 'Pendampingan posyandu dan penyuluhan gizi seimbang untuk balita.',
                'konten' => '<p>Kegiatan posyandu kolaboratif dengan kader posyandu setempat. Tim KKN membantu pencatatan dan edukasi pemberian makanan sehat.</p>',
            ],
        ];

        foreach ($data as $item) {
            Kegiatan::updateOrCreate(['judul' => $item['judul']], $item);
        }
    }

    private function seedGaleri(): void
    {
        Storage::disk('public')->makeDirectory('galeri');
        $sumber = public_path('images/logo.png');

        if (! is_file($sumber)) {
            return;
        }

        $keterangan = [
            'Dokumentasi pembukaan KKN di Kelurahan Mlatinorowito',
            'FGD bersama ketua RT/RW',
            'Pelatihan literasi digital warga',
            'Kegiatan posyandu bersama kader setempat',
            'Tim KKN UMK Mlatinorowito 2026',
        ];

        foreach ($keterangan as $i => $teks) {
            $path = 'galeri/dummy-'.($i + 1).'.png';
            if (! Storage::disk('public')->exists($path)) {
                File::copy($sumber, Storage::disk('public')->path($path));
            }

            Galeri::create(['foto' => $path, 'keterangan' => $teks]);
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
                // Hari ini: 2 orang belum absen (untuk demo rekap)
                if ($day === 0 && $index >= $users->count() - 2) {
                    continue;
                }

                // Random skip 1 hari lalu untuk variasi
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

    private function seedSurat(): void
    {
        $sekretaris = User::query()->whereHas('anggota', fn ($q) => $q->where('jabatan', 'Sekretaris'))->first();

        if (! $sekretaris) {
            return;
        }

        Surat::create([
            'jenis' => 'masuk',
            'tanggal' => now()->subDays(4),
            'asal_tujuan' => 'Kelurahan Mlatinorowito',
            'perihal' => 'Undangan Rapat Koordinasi KKN',
            'keterangan' => 'Surat undangan rapat koordinasi mingguan KKN dengan perangkat kelurahan.',
            'nomor_surat' => '045/Kel-Mlati/VII/2026',
            'user_id' => $sekretaris->id,
        ]);

        Surat::create([
            'jenis' => 'masuk',
            'tanggal' => now()->subDays(2),
            'asal_tujuan' => 'PKK Kelurahan Mlatinorowito',
            'perihal' => 'Permintaan Kerjasama Posyandu',
            'keterangan' => 'Surat permintaan kerjasama pelaksanaan posyandu kolaboratif.',
            'nomor_surat' => '012/PKK/VII/2026',
            'user_id' => $sekretaris->id,
        ]);

        $keluar = [
            [
                'kategori_tujuan' => KategoriTujuanSurat::Kelurahan->value,
                'tanggal' => now()->subDays(3),
                'perihal' => 'Permohonan Izin Kegiatan KKN',
                'keterangan' => "Melalui surat ini kami sampaikan permohonan izin pelaksanaan kegiatan KKN di wilayah Kelurahan Mlatinorowito.\n\nKegiatan meliputi pendampingan posyandu, literasi digital, dan program pemberdayaan masyarakat sesuai program kerja yang telah disosialisasikan.",
                'nomor_rt' => null,
                'nomor_rw' => null,
            ],
            [
                'kategori_tujuan' => KategoriTujuanSurat::Rt->value,
                'nomor_rt' => '03',
                'nomor_rw' => '05',
                'tanggal' => now()->subDays(2),
                'perihal' => 'Undangan Sosialisasi Bank Sampah',
                'keterangan' => 'Kami mengundang Bapak/Ibu warga RT 03 RW 05 untuk hadir pada sosialisasi program bank sampah kelurahan yang akan dilaksanakan di balai RW.',
            ],
            [
                'kategori_tujuan' => KategoriTujuanSurat::Rw->value,
                'nomor_rt' => null,
                'nomor_rw' => '02',
                'tanggal' => now()->subDay(),
                'perihal' => 'Koordinasi Jadwal Pelatihan Digital',
                'keterangan' => 'Bersama surat ini kami mohon kesediaan Bapak/Ibu Ketua RW 02 untuk mengoordinasikan jadwal pelatihan literasi digital bagi warga.',
            ],
            [
                'kategori_tujuan' => KategoriTujuanSurat::Instansi->value,
                'asal_tujuan' => 'Dinas Kesehatan Kabupaten Kudus',
                'tanggal' => now(),
                'perihal' => 'Permohonan Pendampingan Posyandu',
                'keterangan' => 'Kami memohon pendampingan teknis dari Dinas Kesehatan dalam pelaksanaan kegiatan posyandu kolaboratif di Kelurahan Mlatinorowito.',
                'nomor_rt' => null,
                'nomor_rw' => null,
            ],
        ];

        foreach ($keluar as $i => $row) {
            $data = [
                'jenis' => 'keluar',
                'kategori_tujuan' => $row['kategori_tujuan'],
                'nomor_rt' => $row['nomor_rt'] ?? null,
                'nomor_rw' => $row['nomor_rw'] ?? null,
                'tanggal' => $row['tanggal'],
                'perihal' => $row['perihal'],
                'keterangan' => $row['keterangan'],
                'user_id' => $sekretaris->id,
            ];

            $data = PenerimaSurat::lengkapiDataKeluar($data);
            $data['nomor_surat'] = sprintf('%03d/KKN-MLATI/VII/2026', $i + 1);

            $surat = Surat::create($data);

            try {
                $path = GeneratorSuratKeluar::generate($surat, $sekretaris);
                $surat->update(['lampiran' => $path]);
            } catch (\Throwable) {
                // PDF opsional saat seed — data surat tetap tersimpan
            }
        }
    }
}
