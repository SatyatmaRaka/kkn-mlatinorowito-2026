<?php

namespace Tests\Feature\Panel;

use App\Models\Keuangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeuanganTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_keuangan(): void
    {
        $this->get(route('panel.keuangan.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_create_keuangan_record(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('panel.keuangan.store'), [
                'tanggal' => '2026-07-01',
                'jenis' => 'pemasukan',
                'keterangan' => 'Dana awal KKN',
                'nominal' => 500000,
            ])
            ->assertRedirect(route('panel.keuangan.index'));

        $this->assertDatabaseHas('keuangans', [
            'keterangan' => 'Dana awal KKN',
            'nominal' => 500000,
            'user_id' => $user->id,
        ]);
    }

    public function test_keuangan_store_rejects_invalid_jenis(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('panel.keuangan.create'))
            ->post(route('panel.keuangan.store'), [
                'tanggal' => '2026-07-01',
                'jenis' => 'invalid',
                'keterangan' => 'Test',
                'nominal' => 1000,
            ])
            ->assertSessionHasErrors('jenis');
    }

    public function test_admin_can_export_keuangan_csv(): void
    {
        $user = User::factory()->create();

        Keuangan::create([
            'user_id' => $user->id,
            'tanggal' => '2026-07-01',
            'jenis' => 'pemasukan',
            'keterangan' => 'Dana awal',
            'nominal' => 100000,
        ]);

        $this->actingAs($user)
            ->get(route('panel.keuangan.export'))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_keuangan_destroy_soft_deletes_record(): void
    {
        $user = User::factory()->create();
        $keuangan = Keuangan::create([
            'user_id' => $user->id,
            'tanggal' => '2026-07-01',
            'jenis' => 'pengeluaran',
            'keterangan' => 'ATK posko',
            'nominal' => 50000,
        ]);

        $this->actingAs($user)
            ->delete(route('panel.keuangan.destroy', $keuangan))
            ->assertRedirect(route('panel.keuangan.index'));

        $this->assertSoftDeleted('keuangans', ['id' => $keuangan->id]);
        $this->assertSame(0, Keuangan::count());
    }
}
