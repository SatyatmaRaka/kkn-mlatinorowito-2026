<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AnggotaUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('MEMBER_DEFAULT_PASSWORD');

        if (empty($password)) {
            return;
        }

        $koordinator = Anggota::where('jabatan', 'Koordinator Desa')->first();

        if ($koordinator && ! $koordinator->user) {
            User::updateOrCreate(
                ['username' => 'koor_mlati26'],
                [
                    'name' => $koordinator->nama,
                    'password' => Hash::make($password),
                    'role' => UserRole::Koordinator,
                    'anggota_id' => $koordinator->id,
                ]
            );
        }

        $sampleAnggota = Anggota::where('jabatan', 'Humas')->first();

        if ($sampleAnggota && ! $sampleAnggota->user) {
            User::updateOrCreate(
                ['username' => 'anggota_demo'],
                [
                    'name' => $sampleAnggota->nama,
                    'password' => Hash::make($password),
                    'role' => UserRole::Anggota,
                    'anggota_id' => $sampleAnggota->id,
                ]
            );
        }
    }
}
