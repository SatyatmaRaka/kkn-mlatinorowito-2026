<?php

namespace App\Console\Commands;

use App\Enums\PeranPengguna;
use App\Models\Anggota;
use App\Models\Keuangan;
use App\Models\Logbook;
use App\Models\ProgramKerja;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Hapus data operasional untuk uji coba ulang.
 * Pengaturan website dan akun admin tetap dipertahankan.
 */
class ResetDataOperasional extends Command
{
    protected $signature = 'data:reset-operasional {--force : Lewati konfirmasi}';

    protected $description = 'Hapus anggota, logbook, absensi, keuangan, program kerja (admin tetap)';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('Hapus semua data operasional termasuk anggota? Akun admin tetap.')) {
            $this->warn('Dibatalkan.');

            return self::FAILURE;
        }

        DB::transaction(function (): void {
            DB::table('notifications')->delete();
            DB::table('logbooks')->delete();
            DB::table('absensi')->delete();
            DB::table('keuangans')->delete();
            DB::table('program_kerja')->delete();
            DB::table('absensi_tokens')->delete();
            DB::table('sessions')->delete();

            User::query()
                ->where(function ($query): void {
                    $query->where('role', '!=', PeranPengguna::Admin)
                        ->orWhereNotNull('anggota_id');
                })
                ->delete();

            DB::table('anggota')->delete();
        });

        $this->info('Data operasional dihapus.');
        $this->table(
            ['Tabel', 'Sisa'],
            [
                ['Program kerja', (string) ProgramKerja::count()],
                ['Anggota', (string) Anggota::count()],
                ['User (admin)', (string) User::count()],
                ['Keuangan', (string) Keuangan::count()],
                ['Logbook', (string) Logbook::count()],
                ['Absensi', (string) DB::table('absensi')->count()],
            ]
        );

        return self::SUCCESS;
    }
}

