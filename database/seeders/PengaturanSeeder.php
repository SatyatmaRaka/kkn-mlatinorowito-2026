<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use App\Layanan\LayananPengaturan;
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
            'email' => 'kknumk.mlatinorowito26@gmail.com',
            'instagram' => '@kknumk.mlatinorowito.26',
            'tiktok' => '@kkn.mlatinorowito26.umk',
            'periode_kkn' => 'Juli - Agustus 2026',
            'alamat' => 'Kelurahan Mlatinorowito, Kecamatan Kota, Kabupaten Kudus',
            'maps_embed_url' => 'https://maps.google.com/maps?q=Kelurahan%20Mlatinorowito,%20Kudus&t=&z=15&ie=UTF8&iwloc=&output=embed',
            'absensi_jam_mulai' => '06:00',
            'absensi_jam_selesai' => '09:00',
            'nidn_dpl' => '0000000000',
            'desa' => 'Mlatinorowito',
            'kecamatan' => 'Kota',
            'kabupaten' => 'Kudus',
            'tanggal_mulai_kkn' => '2026-07-01',
            'tanggal_selesai_kkn' => '2026-08-04',
        ];

        foreach ($defaults as $key => $value) {
            Pengaturan::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Pengaturan::where('key', 'whatsapp')->delete();

        LayananPengaturan::forget();
    }
}
