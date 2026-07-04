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

    /** Password acak 12 karakter (huruf besar/kecil, angka, simbol) — tidak berbasis nama. */
    public static function passwordAcak(): string
    {
        $upper = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $lower = 'abcdefghjkmnpqrstuvwxyz';
        $digits = '23456789';
        $symbols = '!@#$%&*?';
        $all = $upper.$lower.$digits.$symbols;

        $chars = [
            $upper[random_int(0, strlen($upper) - 1)],
            $lower[random_int(0, strlen($lower) - 1)],
            $digits[random_int(0, strlen($digits) - 1)],
            $symbols[random_int(0, strlen($symbols) - 1)],
        ];

        for ($i = count($chars); $i < 12; $i++) {
            $chars[] = $all[random_int(0, strlen($all) - 1)];
        }

        shuffle($chars);

        return implode('', $chars);
    }

    public static function peranDariJabatan(?string $jabatan): PeranPengguna
    {
        return Jabatan::tryFromValue($jabatan) === Jabatan::KoordinatorDesa
            ? PeranPengguna::Koordinator
            : PeranPengguna::Anggota;
    }
}
