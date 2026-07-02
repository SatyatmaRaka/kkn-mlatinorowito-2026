<?php

namespace Database\Seeders;

use App\Enums\PeranPengguna;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = env('ADMIN_DEFAULT_PASSWORD');

        if (empty($password)) {
            throw new RuntimeException(
                'ADMIN_DEFAULT_PASSWORD must be set in .env before running AdminSeeder.'
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
    }
}
