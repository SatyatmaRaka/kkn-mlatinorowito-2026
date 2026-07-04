<?php

namespace Tests\Unit;

use App\Enums\Jabatan;
use App\Enums\PeranPengguna;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_jabatan_helper_mengembalikan_enum_dari_profil_anggota(): void
    {
        $anggota = Anggota::factory()->create(['jabatan' => Jabatan::Sekretaris->value]);
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->assertSame(Jabatan::Sekretaris, $user->jabatanOrganisasi());
    }

    public function test_sekretaris_tidak_bisa_kelola_cms(): void
    {
        $user = User::factory()->sekretaris()->create();

        $this->assertFalse($user->canManageWebsiteKonten());
        $this->assertFalse($user->canManageKeuangan());
    }

    public function test_wakil_koordinator_bisa_pantau_operasional_tanpa_keuangan(): void
    {
        $user = User::factory()->wakilKoordinator()->create();

        $this->assertTrue($user->canReviewLogbook());
        $this->assertTrue($user->canPantauOperasional());
        $this->assertFalse($user->canManageKeuangan());
        $this->assertFalse($user->isKoordinator());
    }

    public function test_koordinator_desa_dengan_role_anggota_tetap_bisa_review_jika_jabatan_pimpinan(): void
    {
        $anggota = Anggota::factory()->create(['jabatan' => Jabatan::KoordinatorDesa->value]);
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->assertTrue($user->canReviewLogbook());
        $this->assertFalse($user->canManageKeuangan());
    }

    public function test_bendahara_bisa_kelola_keuangan(): void
    {
        $user = User::factory()->bendahara()->create();

        $this->assertTrue($user->canManageKeuangan());
        $this->assertFalse($user->canReviewLogbook());
    }

    public function test_admin_memiliki_akses_penuh(): void
    {
        $user = User::factory()->create(['role' => PeranPengguna::Admin]);

        $this->assertTrue($user->canManageWebsiteKonten());
        $this->assertTrue($user->canManageAnggota());
        $this->assertTrue($user->canReviewLogbook());
        $this->assertTrue($user->canManageKeuangan());
    }
}
