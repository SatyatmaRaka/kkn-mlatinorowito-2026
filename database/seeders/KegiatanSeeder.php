<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'judul' => 'Kegiatan Hari ke-1',
                'tanggal' => now()->subDays(2),
                'deskripsi_singkat' => 'Deskripsi singkat placeholder untuk kegiatan hari pertama.',
                'konten' => '<p>Konten lengkap kegiatan hari pertama akan segera diupdate.</p>',
            ],
            [
                'judul' => 'Kegiatan Hari ke-2',
                'tanggal' => now()->subDays(1),
                'deskripsi_singkat' => 'Deskripsi singkat placeholder untuk kegiatan hari kedua.',
                'konten' => '<p>Konten lengkap kegiatan hari kedua akan segera diupdate.</p>',
            ],
            [
                'judul' => 'Kegiatan Hari ke-3',
                'tanggal' => now(),
                'deskripsi_singkat' => 'Deskripsi singkat placeholder untuk kegiatan hari ketiga.',
                'konten' => '<p>Konten lengkap kegiatan hari ketiga akan segera diupdate.</p>',
            ],
        ];

        foreach ($data as $item) {
            Kegiatan::updateOrCreate(['judul' => $item['judul']], $item);
        }
    }
}