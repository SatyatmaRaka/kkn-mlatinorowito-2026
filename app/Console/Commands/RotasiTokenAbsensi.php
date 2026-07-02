<?php

namespace App\Console\Commands;

use App\Layanan\LayananTokenAbsensi;
use Illuminate\Console\Command;

/**
 * Perintah artisan: buat token QR absensi baru untuk hari ini.
 * Dijalankan otomatis via cron (schedule:run) setiap 00:05.
 */
class RotasiTokenAbsensi extends Command
{
    protected $signature = 'absensi:rotate-token';

    protected $description = 'Generate token absensi QR untuk hari ini';

    public function handle(): int
    {
        $token = LayananTokenAbsensi::getOrCreateForToday();

        $this->info('Token absensi hari ini: '.$token->token);
        $this->line(LayananTokenAbsensi::checkInUrl($token));

        return self::SUCCESS;
    }
}
