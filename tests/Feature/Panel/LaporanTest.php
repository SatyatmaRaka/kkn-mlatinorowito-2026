<?php

namespace Tests\Feature\Panel;

use App\Enums\PeranPengguna;
use App\Models\Anggota;
use App\Models\Logbook;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_laporan(): void
    {
        $admin = User::factory()->create(['role' => PeranPengguna::Admin]);

        $this->actingAs($admin)
            ->get(route('panel.laporan.index'))
            ->assertOk()
            ->assertSee('Laporan KKN');
    }

    public function test_anggota_cannot_view_laporan(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->get(route('panel.laporan.index'))
            ->assertForbidden();
    }

    public function test_live_dasbor_api_returns_json(): void
    {
        $admin = User::factory()->create(['role' => PeranPengguna::Admin]);

        $this->actingAs($admin)
            ->getJson(route('panel.api.live.dasbor'))
            ->assertOk()
            ->assertJsonStructure(['waktu', 'logbook_menunggu', 'absensi_hari_ini']);
    }
}
