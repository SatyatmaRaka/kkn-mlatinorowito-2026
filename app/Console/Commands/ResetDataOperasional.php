<?php

namespace App\Console\Commands;

use App\Enums\PeranPengguna;
use App\Models\Anggota;
use App\Models\Galeri;
use App\Models\Kegiatan;
use App\Models\Keuangan;
use App\Models\Logbook;
use App\Models\ProgramKerja;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Hapus data operasional untuk uji coba ulang.
 * Program kerja, kegiatan, pengaturan website, dan akun admin tetap dipertahankan.
 */
class ResetDataOperasional extends Command
{
    protected $signature = 'data:reset-operasional {--force : Lewati konfirmasi}';

    protected $description = 'Hapus anggota, keuangan, logbook, absensi, galeri (proker & kegiatan tetap)';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('Hapus semua data operasional? Program kerja & kegiatan tidak dihapus.')) {
            $this->warn('Dibatalkan.');

            return self::FAILURE;
        }

        DB::transaction(function (): void {
            DB::table('notifications')->delete();
            DB::table('logbooks')->delete();
            DB::table('absensi')->delete();
            DB::table('keuangans')->delete();
            DB::table('galeri')->delete();
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
                ['Kegiatan', (string) Kegiatan::count()],
                ['Anggota', (string) Anggota::count()],
                ['User (admin)', (string) User::count()],
                ['Keuangan', (string) Keuangan::count()],
                ['Galeri', (string) Galeri::count()],
                ['Logbook', (string) Logbook::count()],
                ['Absensi', (string) DB::table('absensi')->count()],
            ]
        );

        return self::SUCCESS;
    }
}
