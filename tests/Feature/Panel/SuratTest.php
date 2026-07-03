<?php

namespace Tests\Feature\Panel;

use App\Models\Anggota;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuratTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_surat_index(): void
    {
        $this->get(route('panel.surat.index'))->assertRedirect(route('login'));
    }

    public function test_sekretaris_can_access_surat_index(): void
    {
        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->get(route('panel.surat.index'))
            ->assertOk()
            ->assertSee('Surat Menyurat');
    }

    public function test_anggota_without_jabatan_cms_cannot_access_surat(): void
    {
        $anggota = Anggota::factory()->create(['jabatan' => 'Humas']);
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->get(route('panel.surat.index'))
            ->assertForbidden();
    }

    public function test_sekretaris_can_create_surat(): void
    {
        Storage::fake('public');

        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->post(route('panel.surat.store'), [
                'jenis' => 'masuk',
                'nomor_surat' => '001/KKN/2026',
                'tanggal' => now()->toDateString(),
                'asal_tujuan' => 'Kelurahan Mlatinorowito',
                'perihal' => 'Undangan rapat koordinasi',
                'keterangan' => 'Surat undangan kegiatan KKN',
            ])
            ->assertRedirect(route('panel.surat.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('surat', [
            'jenis' => 'masuk',
            'perihal' => 'Undangan rapat koordinasi',
            'user_id' => $user->id,
        ]);
    }

    public function test_sekretaris_can_update_and_delete_surat(): void
    {
        $user = User::factory()->sekretaris()->create();
        $surat = Surat::factory()->masuk()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->put(route('panel.surat.update', $surat), [
                'jenis' => 'keluar',
                'nomor_surat' => '002/KKN/2026',
                'tanggal' => now()->toDateString(),
                'asal_tujuan' => 'Dinas Pendidikan Kudus',
                'perihal' => 'Balasan surat masuk',
            ])
            ->assertRedirect(route('panel.surat.index'));

        $this->assertDatabaseHas('surat', [
            'id' => $surat->id,
            'jenis' => 'keluar',
            'perihal' => 'Balasan surat masuk',
        ]);

        $this->actingAs($user)
            ->delete(route('panel.surat.destroy', $surat))
            ->assertRedirect(route('panel.surat.index'));

        $this->assertDatabaseMissing('surat', ['id' => $surat->id]);
    }

    public function test_surat_store_rejects_invalid_jenis(): void
    {
        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->from(route('panel.surat.create'))
            ->post(route('panel.surat.store'), [
                'jenis' => 'invalid',
                'tanggal' => now()->toDateString(),
                'asal_tujuan' => 'Test',
                'perihal' => 'Test',
            ])
            ->assertSessionHasErrors('jenis');
    }
}
