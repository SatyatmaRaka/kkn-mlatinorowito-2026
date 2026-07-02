<?php

namespace Tests\Feature\Panel;

use App\Enums\Jabatan;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_anggota_without_jabatan_cannot_access_cms(): void
    {
        $anggota = Anggota::factory()->create(['jabatan' => Jabatan::Humas->value]);
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->get(route('panel.anggota.index'))
            ->assertForbidden();
    }

    public function test_anggota_cannot_access_kegiatan_cms(): void
    {
        $anggota = Anggota::factory()->create(['jabatan' => Jabatan::Humas->value]);
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->get(route('panel.kegiatan.index'))
            ->assertForbidden();
    }

    public function test_anggota_cannot_access_pengaturan(): void
    {
        $anggota = Anggota::factory()->create(['jabatan' => Jabatan::Humas->value]);
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->get(route('panel.pengaturan.index'))
            ->assertForbidden();
    }

    public function test_sekretaris_can_access_cms(): void
    {
        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->get(route('panel.anggota.index'))
            ->assertOk();
    }

    public function test_sekretaris_cannot_access_keuangan(): void
    {
        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->get(route('panel.keuangan.index'))
            ->assertForbidden();
    }

    public function test_anggota_without_jabatan_cannot_access_keuangan(): void
    {
        $anggota = Anggota::factory()->create(['jabatan' => Jabatan::Humas->value]);
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->get(route('panel.keuangan.index'))
            ->assertForbidden();
    }

    public function test_bendahara_can_access_keuangan(): void
    {
        $user = User::factory()->bendahara()->create();

        $this->actingAs($user)
            ->get(route('panel.keuangan.index'))
            ->assertOk();
    }

    public function test_koordinator_can_access_keuangan(): void
    {
        $user = User::factory()->koordinator()->create();

        $this->actingAs($user)
            ->get(route('panel.keuangan.index'))
            ->assertOk();
    }
}
