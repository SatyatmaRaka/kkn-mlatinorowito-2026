<?php

namespace App\Console\Commands;

use App\Services\AbsensiTokenService;
use Illuminate\Console\Command;

class RotateAbsensiTokenCommand extends Command
{
    protected $signature = 'absensi:rotate-token';

    protected $description = 'Generate token absensi QR untuk hari ini';

    public function handle(): int
    {
        $token = AbsensiTokenService::getOrCreateForToday();

        $this->info('Token absensi hari ini: '.$token->token);
        $this->line(AbsensiTokenService::checkInUrl($token));

        return self::SUCCESS;
    }
}
