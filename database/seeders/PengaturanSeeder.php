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
            'instagram' => '@kknumk.mlatinorowito.26',
            'periode_kkn' => 'Juli - Agustus 2026',
            'whatsapp' => '6281234567890',
            'alamat' => 'Kelurahan Mlatinorowito, Kecamatan Kota, Kabupaten Kudus',
            'maps_embed_url' => 'https://maps.google.com/maps?q=Kelurahan%20Mlatinorowito,%20Kudus&t=&z=15&ie=UTF8&iwloc=&output=embed',
            'absensi_jam_mulai' => '06:00',
            'absensi_jam_selesai' => '09:00',
        ];

        foreach ($defaults as $key => $value) {
            Pengaturan::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        PengaturanService::forget();
    }
}
