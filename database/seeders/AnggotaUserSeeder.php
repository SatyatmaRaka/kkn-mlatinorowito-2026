<?php

namespace Database\Seeders;

use App\Enums\Jabatan;
use App\Enums\PeranPengguna;
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

        $koordinator = Anggota::where('jabatan', Jabatan::KoordinatorDesa->value)->first();

        if ($koordinator && ! $koordinator->user) {
            User::updateOrCreate(
                ['username' => 'koor_mlati26'],
                [
                    'name' => $koordinator->nama,
                    'password' => Hash::make($password),
                    'role' => PeranPengguna::Koordinator,
                    'anggota_id' => $koordinator->id,
                ]
            );
        }

        $sampleAnggota = Anggota::where('jabatan', Jabatan::Humas->value)->first();

        if ($sampleAnggota && ! $sampleAnggota->user) {
            User::updateOrCreate(
                ['username' => 'anggota_demo'],
                [
                    'name' => $sampleAnggota->nama,
                    'password' => Hash::make($password),
                    'role' => PeranPengguna::Anggota,
                    'anggota_id' => $sampleAnggota->id,
                ]
            );
        }
    }
}
