<?php

namespace Database\Seeders;

use App\Models\ProgramKerja;
use Illuminate\Database\Seeder;

class ProgramKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['judul' => 'Program Kerja 1', 'tema' => null, 'deskripsi' => 'Akan segera diumumkan setelah diskusi kelompok final', 'icon' => '📋', 'pic' => null, 'status' => 'Coming Soon', 'urutan' => 1],
            ['judul' => 'Program Kerja 2', 'tema' => null, 'deskripsi' => 'Akan segera diumumkan setelah diskusi kelompok final', 'icon' => '📋', 'pic' => null, 'status' => 'Coming Soon', 'urutan' => 2],
            ['judul' => 'Program Kerja 3', 'tema' => null, 'deskripsi' => 'Akan segera diumumkan setelah diskusi kelompok final', 'icon' => '📋', 'pic' => null, 'status' => 'Coming Soon', 'urutan' => 3],
            ['judul' => 'Program Kerja 4', 'tema' => null, 'deskripsi' => 'Akan segera diumumkan setelah diskusi kelompok final', 'icon' => '📋', 'pic' => null, 'status' => 'Coming Soon', 'urutan' => 4],
            ['judul' => 'Program Kerja 5', 'tema' => null, 'deskripsi' => 'Akan segera diumumkan setelah diskusi kelompok final', 'icon' => '📋', 'pic' => null, 'status' => 'Coming Soon', 'urutan' => 5],
        ];

        foreach ($data as $item) {
            ProgramKerja::updateOrCreate(['judul' => $item['judul']], $item);
        }
    }
}
