<?php

namespace Tests\Feature\Panel;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_admin_sees_admin_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Ringkasan Dasbor');
    }

    public function test_sekretaris_sees_divisi_dashboard(): void
    {
        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Sekretaris')
            ->assertSee('Tugas Divisi Sekretaris');
    }

    public function test_koordinator_sees_koordinator_dashboard(): void
    {
        $user = User::factory()->koordinator()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dasbor Koordinator')
            ->assertSee('Logbook Menunggu Review');
    }

    public function test_bendahara_sees_bendahara_dashboard(): void
    {
        $user = User::factory()->bendahara()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dasbor Bendahara')
            ->assertSee('Saldo Kas');
    }

    public function test_wakil_koordinator_sees_pantau_dashboard(): void
    {
        $user = User::factory()->wakilKoordinator()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dasbor Wakil Koordinator')
            ->assertSee('Logbook Menunggu Review');
    }

    public function test_wakil_koordinator_can_access_rekap_absensi(): void
    {
        $user = User::factory()->wakilKoordinator()->create();

        $this->actingAs($user)
            ->get(route('panel.absensi.rekap'))
            ->assertOk();
    }

    public function test_humas_sees_divisi_dashboard(): void
    {
        $user = User::factory()->anggota()->create([
            'anggota_id' => \App\Models\Anggota::factory()->create(['jabatan' => 'Humas'])->id,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Humas')
            ->assertSee('Tugas Divisi Humas');
    }
}
