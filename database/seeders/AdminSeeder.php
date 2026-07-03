<?php

namespace Database\Seeders;

use App\Enums\PeranPengguna;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

/**
 * Buat akun admin awal saat seeding.
 * Password awal dari ADMIN_DEFAULT_PASSWORD (.env); ganti lewat Panel → Pengaturan.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('ADMIN_DEFAULT_PASSWORD');

        if (empty($password)) {
            throw new RuntimeException(
                'ADMIN_DEFAULT_PASSWORD harus diisi di .env sebelum menjalankan seed.'
            );
        }

        User::updateOrCreate(
            ['username' => 'kkn_mlati26'],
            [
                'name' => 'Admin KKN Mlatinorowito',
                'password' => Hash::make($password),
                'role' => PeranPengguna::Admin,
            ]
        );

        if ($this->command) {
            $this->command->info('Akun admin dibuat otomatis.');
            $this->command->line('  Username : kkn_mlati26');
            $this->command->line('  Password : nilai ADMIN_DEFAULT_PASSWORD di .env');
            $this->command->warn('  Ganti password lewat Panel → Pengaturan → Keamanan Akun setelah login.');
        }
    }
}
