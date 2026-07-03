<?php

namespace Database\Seeders;

use App\Models\Anggota;
use Illuminate\Database\Seeder;

class AnggotaSeeder extends Seeder
{
    /**
     * Data anggota KKN Mlatinorowito 2026.
     *
     * Divisi:
     * - Koordinator Desa & Wakil Koordinator
     * - PDD, Perlengkapan, Humas, Sekretaris, Bendahara
     */
    public function run(): void
    {
        $anggota = [
            // Pimpinan
            ['nama' => 'Satyatma Raka Wiratama',     'jurusan' => 'Fakultas Teknik',                         'jabatan' => 'Koordinator Desa',  'urutan' => 1],
            ['nama' => 'M Maulana Afriza',           'jurusan' => 'Fakultas Teknik',                         'jabatan' => 'Wakil Koordinator', 'urutan' => 2],
            // Sekretaris
            ['nama' => 'Anggun Hana Charenta',       'jurusan' => 'Fakultas Keguruan dan Ilmu Pendidikan',   'jabatan' => 'Sekretaris',        'urutan' => 3],
            ['nama' => 'Sabti Riguna Hidayah',       'jurusan' => 'Fakultas Psikologi',                      'jabatan' => 'Sekretaris',        'urutan' => 4],
            // Bendahara
            ['nama' => 'Karimatun Naja',             'jurusan' => 'Fakultas Ekonomi dan Bisnis',             'jabatan' => 'Bendahara',         'urutan' => 5],
            // Humas
            ['nama' => 'Valliant Prihastio',          'jurusan' => 'Fakultas Ekonomi dan Bisnis',             'jabatan' => 'Humas',             'urutan' => 6],
            ['nama' => 'Novita Nawang Sari',         'jurusan' => 'Fakultas Teknik',                         'jabatan' => 'Humas',             'urutan' => 7],
            // PDD
            ['nama' => 'Anggit Jelang',              'jurusan' => 'Fakultas Hukum',                          'jabatan' => 'PDD',               'urutan' => 8],
            ['nama' => 'Citra Anindya Nathannasywa', 'jurusan' => 'Fakultas Ekonomi dan Bisnis',             'jabatan' => 'PDD',               'urutan' => 9],
            // Perlengkapan
            ['nama' => 'Fira Naelatul Chamidah',     'jurusan' => 'Fakultas Keguruan dan Ilmu Pendidikan',   'jabatan' => 'Perlengkapan',      'urutan' => 10],
            ['nama' => 'Hanna Dwi Aryani',           'jurusan' => 'Fakultas Ekonomi dan Bisnis',             'jabatan' => 'Perlengkapan',      'urutan' => 11],
        ];

        $validUrutan = array_column($anggota, 'urutan');

        foreach ($anggota as $data) {
            Anggota::updateOrCreate(
                ['urutan' => $data['urutan']],
                $data
            );
        }

        Anggota::query()
            ->whereNotIn('urutan', $validUrutan)
            ->whereDoesntHave('user')
            ->whereDoesntHave('logbooks')
            ->whereDoesntHave('absensi')
            ->delete();

        $namaLama = [
            'Mohammad Maulana Afriza',
            'Anggit Jelang Fitrio Dwi Saput',
            'Anggun',
        ];

        Anggota::query()
            ->whereIn('nama', $namaLama)
            ->whereDoesntHave('user')
            ->whereDoesntHave('logbooks')
            ->whereDoesntHave('absensi')
            ->delete();
    }
}
