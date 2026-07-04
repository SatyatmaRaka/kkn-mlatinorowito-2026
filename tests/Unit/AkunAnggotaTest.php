<?php

namespace Tests\Unit;

use App\Enums\PeranPengguna;
use App\Penunjang\AkunAnggota;
use Tests\TestCase;

class AkunAnggotaTest extends TestCase
{
    public function test_username_dari_nama_depan(): void
    {
        $this->assertSame('satyatma', AkunAnggota::usernameDariNama('Satyatma Raka Wiratama'));
        $this->assertSame('maulana', AkunAnggota::usernameDariNama('M Maulana Afriza'));
    }

    public function test_username_unik_menghindari_bentrok(): void
    {
        $this->assertSame('anggun', AkunAnggota::usernameUnik('Anggun Hana', ['admin']));
        $this->assertSame('anggun2', AkunAnggota::usernameUnik('Anggun Lain', ['anggun']));
    }

    public function test_password_dari_nama(): void
    {
        $this->assertSame('SatyatmaMlati26!', AkunAnggota::passwordDariNama('Satyatma Raka Wiratama'));
        $this->assertSame('MaulanaMlati26!', AkunAnggota::passwordDariNama('M Maulana Afriza'));
    }

    public function test_koordinator_desa_dapat_peran_koordinator(): void
    {
        $this->assertSame(
            PeranPengguna::Koordinator,
            AkunAnggota::peranDariJabatan('Koordinator Desa')
        );
        $this->assertSame(
            PeranPengguna::Anggota,
            AkunAnggota::peranDariJabatan('Sekretaris')
        );
    }
}
