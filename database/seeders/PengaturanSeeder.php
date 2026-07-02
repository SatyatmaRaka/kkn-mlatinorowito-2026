<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use App\Services\PengaturanService;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            'nama_dpl' => 'Dr. Imaniar Purbasari, M.Pd.',
            'nama_kelompok' => 'KKN Mlatinorowito 2026',
            'tagline' => 'Berdampak dalam Membangun Desa Mandiri dan Berkelanjutan',
            'email' => 'kkn.mlatinorowito2026@gmail.com',
            'instagram' => '@kkn_mlatinorowito2026',
            'periode_kkn' => 'Juli - Agustus 2026',
        ];

        foreach ($defaults as $key => $value) {
            Pengaturan::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        PengaturanService::forget();
    }
}
