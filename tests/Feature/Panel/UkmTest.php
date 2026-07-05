<?php

namespace Tests\Feature\Panel;

use App\Models\Ukm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UkmTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_crud_ukm(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('panel.ukm.store'), [
                'nama_usaha' => 'Warung Bu Siti',
                'jenis_usaha' => 'Kuliner',
                'rata_rata_omzet' => 'Rp 3 juta',
                'urutan' => 1,
            ])
            ->assertRedirect(route('panel.ukm.index'));

        $ukm = Ukm::first();

        $this->actingAs($admin)
            ->put(route('panel.ukm.update', $ukm), [
                'nama_usaha' => 'Warung Bu Siti Updated',
                'jenis_usaha' => 'Kuliner',
                'urutan' => 1,
            ])
            ->assertRedirect(route('panel.ukm.index'));

        $this->assertDatabaseHas('ukm', ['nama_usaha' => 'Warung Bu Siti Updated']);

        $this->actingAs($admin)
            ->delete(route('panel.ukm.destroy', $ukm))
            ->assertRedirect(route('panel.ukm.index'));

        $this->assertSoftDeleted('ukm', ['id' => $ukm->id]);
    }

    public function test_anggota_cannot_access_ukm(): void
    {
        $user = User::factory()->anggota()->create(['anggota_id' => \App\Models\Anggota::factory()->create()->id]);

        $this->actingAs($user)
            ->get(route('panel.ukm.index'))
            ->assertForbidden();
    }

    public function test_export_csv_benar(): void
    {
        $admin = User::factory()->create();
        Ukm::create(['nama_usaha' => 'Toko A', 'jenis_usaha' => 'Retail', 'urutan' => 1]);

        $response = $this->actingAs($admin)->get(route('panel.ukm.export'));
        $response->assertOk();
        $this->assertStringContainsString('Toko A', $response->streamedContent());
    }

    public function test_cetak_menampilkan_data(): void
    {
        $admin = User::factory()->create();
        Ukm::create(['nama_usaha' => 'UMKM B', 'jenis_usaha' => 'Kerajinan', 'urutan' => 1]);

        $this->actingAs($admin)
            ->get(route('panel.ukm.cetak'))
            ->assertOk()
            ->assertSee('UMKM B')
            ->assertSee('Pemetaan Potensi', false);
    }
}
