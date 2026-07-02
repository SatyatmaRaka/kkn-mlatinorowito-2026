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
            ['nama' => 'Raka', 'jurusan' => 'Teknik Informatika', 'jabatan' => 'Koordinator Desa', 'urutan' => 1],
            ['nama' => 'Hanna', 'jurusan' => 'Manajemen', 'jabatan' => 'Anggota', 'urutan' => 2],
            ['nama' => 'Novita', 'jurusan' => 'Teknik Industri', 'jabatan' => 'Anggota', 'urutan' => 3],
            ['nama' => 'Valliant', 'jurusan' => 'Manajemen', 'jabatan' => 'Anggota', 'urutan' => 4],
            ['nama' => 'Anggit', 'jurusan' => 'Hukum', 'jabatan' => 'Anggota', 'urutan' => 5],
            ['nama' => 'Afriza', 'jurusan' => 'Sistem Informasi', 'jabatan' => 'Anggota', 'urutan' => 6],
            ['nama' => 'Sabti Riguna Hidayah', 'jurusan' => 'Psikologi', 'jabatan' => 'Anggota', 'urutan' => 7],
            ['nama' => 'Naja', 'jurusan' => 'Akuntansi', 'jabatan' => 'Anggota', 'urutan' => 8],
            ['nama' => 'Anggun Hana', 'jurusan' => 'PGSD', 'jabatan' => 'Anggota', 'urutan' => 9],
            ['nama' => 'Citra', 'jurusan' => 'Manajemen', 'jabatan' => 'Anggota', 'urutan' => 10],
            ['nama' => 'Fira', 'jurusan' => 'PGSD', 'jabatan' => 'Anggota', 'urutan' => 11],
            ['nama' => '[Nama Anggota 12]', 'jurusan' => '[Jurusan]', 'jabatan' => 'Anggota', 'urutan' => 12],
        ];

        foreach ($anggota as $data) {
            Anggota::updateOrCreate(
                ['nama' => $data['nama']],
                $data
            );
        }
    }
}
