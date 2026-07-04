<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\Keuangan;
use App\Models\Logbook;
use App\Models\ProgramKerja;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Hapus data logbook, absensi, keuangan, dan program kerja.
 * Akun admin, anggota, dan pengaturan website tidak dihapus.
 */
class BersihkanDataModul extends Command
{
    protected $signature = 'data:bersihkan-modul {--force : Lewati konfirmasi}';

    protected $description = 'Hapus data logbook, absensi, keuangan, dan program kerja';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('PERMANEN — data logbook, absensi, keuangan, dan program kerja akan dihapus total dan TIDAK BISA dipulihkan (bukan soft delete). Lanjutkan?')) {
            $this->warn('Dibatalkan.');

            return self::FAILURE;
        }

        DB::transaction(function (): void {
            DB::table('notifications')->delete();
            Logbook::query()->forceDelete();
            Absensi::query()->delete();
            Keuangan::query()->forceDelete();
            ProgramKerja::query()->delete();
        });

        $this->info('Data modul operasional berhasil dibersihkan.');
        $this->table(
            ['Modul', 'Sisa'],
            [
                ['Logbook', (string) Logbook::count()],
                ['Absensi', (string) Absensi::count()],
                ['Keuangan', (string) Keuangan::count()],
                ['Program Kerja', (string) ProgramKerja::count()],
            ]
        );

        return self::SUCCESS;
    }
}
