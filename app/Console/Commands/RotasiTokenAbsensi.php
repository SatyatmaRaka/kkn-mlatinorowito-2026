<?php

namespace App\Console\Commands;

use App\Layanan\LayananTokenAbsensi;
use Illuminate\Console\Command;

/**
 * Perintah artisan: buat ulang token QR absensi (manual, jika QR bocor).
 */
class RotasiTokenAbsensi extends Command
{
    protected $signature = 'absensi:rotate-token';

    protected $description = 'Buat ulang token QR absensi posko (manual)';

    public function handle(): int
    {
        $token = LayananTokenAbsensi::regenerate();

        $this->info('Token absensi baru: '.$token->token);
        $this->line(LayananTokenAbsensi::checkInUrl($token));
        $this->warn('QR lama tidak valid lagi — cetak/tampilkan QR baru di posko.');

        return self::SUCCESS;
    }
}
