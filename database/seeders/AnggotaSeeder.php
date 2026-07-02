<?php

namespace Database\Seeders;

use App\Models\Anggota;
use Illuminate\Database\Seeder;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anggota = [
            ['nama' => 'Satyatma Raka Wiratama',         'jurusan' => 'Sistem Informasi',               'jabatan' => 'Koordinator Desa',  'urutan' => 1],
            ['nama' => 'Mohammad Maulana Afriza',        'jurusan' => 'Teknik Industri',                'jabatan' => 'Wakil Koordinator', 'urutan' => 2],
            ['nama' => 'Anggun',                         'jurusan' => 'Manajemen',                      'jabatan' => 'Sekretaris',        'urutan' => 3],
            ['nama' => 'Sabti Riguna Hidayah',           'jurusan' => 'Psikologi',                      'jabatan' => 'Sekretaris',        'urutan' => 4],
            ['nama' => 'Karimatun Naja',                 'jurusan' => 'Akuntansi',                      'jabatan' => 'Bendahara',         'urutan' => 5],
            ['nama' => 'Valliant Prihastio',             'jurusan' => 'Manajemen',                      'jabatan' => 'Humas',             'urutan' => 6],
            ['nama' => 'Novita Nawang Sari',             'jurusan' => 'Psikologi',                      'jabatan' => 'Humas',             'urutan' => 7],
            ['nama' => 'Anggit Jelang Fitrio Dwi Saput', 'jurusan' => 'Ilmu Hukum',                    'jabatan' => 'PDD',               'urutan' => 8],
            ['nama' => 'Citra Anindya Nathannasywa',     'jurusan' => 'Manajemen',                      'jabatan' => 'PDD',               'urutan' => 9],
            ['nama' => 'Fira Naelatul Chamidah',         'jurusan' => 'Teknik Informatika',             'jabatan' => 'Perlengkapan',      'urutan' => 10],
            ['nama' => 'Anggun Hana Charenta',           'jurusan' => 'Pendidikan Guru Sekolah Dasar',  'jabatan' => 'Perlengkapan',      'urutan' => 11],
        ];

        foreach ($anggota as $data) {
            Anggota::updateOrCreate(
                ['nama' => $data['nama']],
                $data
            );
        }
    }
}
