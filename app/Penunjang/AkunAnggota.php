<?php

namespace App\Penunjang;

use App\Enums\Jabatan;
use App\Enums\PeranPengguna;
use Illuminate\Support\Str;

/**
 * Pembantu username & password awal akun anggota KKN.
 */
class AkunAnggota
{
    /** Ambil nama depan untuk username (abaikan inisial satu huruf, mis. "M Maulana"). */
    public static function usernameDariNama(string $nama): string
    {
        $parts = array_values(array_filter(preg_split('/\s+/', trim($nama)) ?: []));

        $depan = $parts[0] ?? 'anggota';

        if (strlen($depan) <= 1 && isset($parts[1])) {
            $depan = $parts[1];
        }

        $slug = Str::slug(Str::lower($depan), '');

        return $slug !== '' ? $slug : 'anggota';
    }

    /** Username unik; tambah angka jika bentrok. */
    public static function usernameUnik(string $nama, array $sudahDipakai): string
    {
        $base = self::usernameDariNama($nama);
        $username = $base;
        $i = 2;

        while (in_array($username, $sudahDipakai, true)) {
            $username = $base.$i;
            $i++;
        }

        return $username;
    }

    /**
     * Password awal: {NamaDepan}Mlati26!
     * Unik per orang, mudah diingat, memenuhi kombinasi huruf besar/kecil, angka, simbol.
     */
    public static function passwordDariNama(string $nama): string
    {
        $parts = array_values(array_filter(preg_split('/\s+/', trim($nama)) ?: []));

        $depan = $parts[0] ?? 'Anggota';

        if (strlen($depan) <= 1 && isset($parts[1])) {
            $depan = $parts[1];
        }

        $kapital = Str::ucfirst(Str::lower($depan));

        return $kapital.'Mlati26!';
    }

    public static function peranDariJabatan(?string $jabatan): PeranPengguna
    {
        return Jabatan::tryFromValue($jabatan) === Jabatan::KoordinatorDesa
            ? PeranPengguna::Koordinator
            : PeranPengguna::Anggota;
    }
}
