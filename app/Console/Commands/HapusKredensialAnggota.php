<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Hapus file plaintext kredensial akun anggota setelah dibagikan.
 */
class HapusKredensialAnggota extends Command
{
    protected $signature = 'kredensial:hapus';

    protected $description = 'Hapus file plaintext kredensial akun anggota dari server';

    public function handle(): int
    {
        $path = storage_path('app/private/akun-anggota-kredensial.txt');

        if (! File::exists($path)) {
            $this->warn('File tidak ditemukan.');

            return self::SUCCESS;
        }

        File::delete($path);

        $this->info('File kredensial akun anggota berhasil dihapus.');

        return self::SUCCESS;
    }
}
