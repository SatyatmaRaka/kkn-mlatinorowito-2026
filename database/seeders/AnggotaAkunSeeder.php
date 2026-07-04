<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\User;
use App\Penunjang\AkunAnggota;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

/**
 * Buat akun login untuk setiap anggota KKN (setelah AnggotaSeeder).
 * Username: nama depan (lowercase). Password: {NamaDepan}Mlati26!
 */
class AnggotaAkunSeeder extends Seeder
{
    public function run(): void
    {
        $dipakai = User::query()->pluck('username')->all();
        $baru = [];
        $dilewati = [];

        foreach (Anggota::query()->orderBy('urutan')->get() as $anggota) {
            if ($anggota->user) {
                $dilewati[] = $anggota->nama;

                continue;
            }

            $username = AkunAnggota::usernameUnik($anggota->nama, $dipakai);
            $password = AkunAnggota::passwordDariNama($anggota->nama);

            User::create([
                'name' => $anggota->nama,
                'username' => $username,
                'password' => Hash::make($password),
                'role' => AkunAnggota::peranDariJabatan($anggota->jabatan),
                'anggota_id' => $anggota->id,
                'wajib_ganti_password' => true,
            ]);

            $dipakai[] = $username;
            $baru[] = [
                'nama' => $anggota->nama,
                'jabatan' => $anggota->jabatan,
                'username' => $username,
                'password' => $password,
                'role' => AkunAnggota::peranDariJabatan($anggota->jabatan)->value,
            ];
        }

        if ($baru !== []) {
            $this->tulisKredensial($baru);
        }

        if ($this->command) {
            if ($baru === []) {
                $this->command->warn('Semua anggota sudah punya akun — tidak ada akun baru dibuat.');

                return;
            }

            $this->command->info('Akun anggota dibuat ('.count($baru).' orang):');
            $this->command->newLine();

            foreach ($baru as $row) {
                $this->command->line(sprintf(
                    '  %-28s | %-12s | %s',
                    $row['nama'],
                    $row['username'],
                    $row['password']
                ));
            }

            $this->command->newLine();
            $this->command->line('  Daftar lengkap disimpan di: storage/app/private/akun-anggota-kredensial.txt');
            $this->command->warn('  Bagikan password secara pribadi; anggota disarankan ganti setelah login pertama.');
            $this->command->warn('  WAJIB: setelah kredensial dibagikan ke masing-masing anggota, hapus file dari server dengan: php artisan kredensial:hapus');
        }
    }

    /** @param  list<array{nama: string, jabatan: string, username: string, password: string, role: string}>  $baru */
    private function tulisKredensial(array $baru): void
    {
        $path = storage_path('app/private/akun-anggota-kredensial.txt');

        File::ensureDirectoryExists(dirname($path));

        $lines = [
            'KKN Mlatinorowito 2026 — Kredensial Akun Anggota',
            'Dibuat: '.now()->timezone('Asia/Jakarta')->format('d M Y H:i').' WIB',
            'Pola password: {NamaDepan}Mlati26!',
            str_repeat('-', 72),
            '',
        ];

        foreach ($baru as $row) {
            $lines[] = sprintf('Nama     : %s', $row['nama']);
            $lines[] = sprintf('Jabatan  : %s', $row['jabatan']);
            $lines[] = sprintf('Username : %s', $row['username']);
            $lines[] = sprintf('Password : %s', $row['password']);
            $lines[] = sprintf('Role     : %s', $row['role']);
            $lines[] = '';
        }

        File::put($path, implode(PHP_EOL, $lines));
    }
}
