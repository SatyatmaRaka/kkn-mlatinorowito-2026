<?php

namespace Tests\Feature\Panel;

use App\Enums\PeranPengguna;
use App\Models\Anggota;
use App\Models\Keuangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedKeuanganContoh();
    }

    private function seedKeuanganContoh(): void
    {
        $user = User::factory()->create();

        Keuangan::create([
            'user_id' => $user->id,
            'diubah_oleh' => $user->id,
            'tanggal' => now()->toDateString(),
            'jenis' => 'pemasukan',
            'keterangan' => 'Dana contoh laporan',
            'nominal' => 1_500_000,
        ]);
    }

    public function test_admin_can_view_laporan(): void
    {
        $admin = User::factory()->create(['role' => PeranPengguna::Admin]);

        $this->actingAs($admin)
            ->get(route('panel.laporan.index'))
            ->assertOk()
            ->assertSee('Laporan KKN')
            ->assertSee('1.500.000')
            ->assertSee('Saldo periode');
    }

    public function test_anggota_cannot_view_laporan(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->get(route('panel.laporan.index'))
            ->assertForbidden();
    }

    public function test_wakil_koordinator_can_view_laporan_without_keuangan(): void
    {
        $wakil = User::factory()->wakilKoordinator()->create();

        $this->actingAs($wakil)
            ->get(route('panel.laporan.index'))
            ->assertOk()
            ->assertSee('Absensi')
            ->assertSee('Logbook')
            ->assertDontSee('Saldo periode')
            ->assertDontSee('1.500.000')
            ->assertDontSee('Export CSV Keuangan');
    }

    public function test_bendahara_sees_keuangan_in_laporan(): void
    {
        $bendahara = User::factory()->bendahara()->create();

        $this->actingAs($bendahara)
            ->get(route('panel.laporan.keuangan'))
            ->assertOk()
            ->assertSee('1.500.000')
            ->assertSee('Saldo periode');
    }

    public function test_koordinator_sees_keuangan_in_laporan(): void
    {
        $koordinator = User::factory()->koordinator()->create();

        $this->actingAs($koordinator)
            ->get(route('panel.laporan.index'))
            ->assertOk()
            ->assertSee('1.500.000')
            ->assertSee('Saldo periode');
    }

    public function test_wakil_koordinator_export_excludes_keuangan_rows(): void
    {
        $wakil = User::factory()->wakilKoordinator()->create();

        $response = $this->actingAs($wakil)
            ->get(route('panel.laporan.export'));

        $response->assertOk();
        $this->assertStringNotContainsString('Pemasukan (Rp)', $response->streamedContent());
        $this->assertStringNotContainsString('1500000', $response->streamedContent());
    }

    public function test_admin_export_includes_keuangan_rows(): void
    {
        $admin = User::factory()->create(['role' => PeranPengguna::Admin]);

        $response = $this->actingAs($admin)
            ->get(route('panel.laporan.export'));

        $response->assertOk();
        $this->assertStringContainsString('Pemasukan (Rp)', $response->streamedContent());
        $this->assertStringContainsString('1500000', $response->streamedContent());
    }

    public function test_live_dasbor_api_returns_json(): void
    {
        $admin = User::factory()->create(['role' => PeranPengguna::Admin]);

        $this->actingAs($admin)
            ->getJson(route('panel.api.live.dasbor'))
            ->assertOk()
            ->assertJsonStructure(['waktu', 'logbook_menunggu', 'absensi_hari_ini', 'saldo_bulan_ini']);
    }

    public function test_live_dasbor_api_hides_saldo_for_wakil_koordinator(): void
    {
        $wakil = User::factory()->wakilKoordinator()->create();

        $this->actingAs($wakil)
            ->getJson(route('panel.api.live.dasbor'))
            ->assertOk()
            ->assertJsonMissing(['saldo_bulan_ini']);
    }
}
