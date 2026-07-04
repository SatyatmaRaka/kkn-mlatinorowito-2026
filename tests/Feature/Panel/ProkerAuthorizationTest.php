<?php

namespace Tests\Feature\Panel;

use App\Enums\Jabatan;
use App\Models\Anggota;
use App\Models\ProgramKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProkerAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function anggotaBiasa(): User
    {
        $anggota = Anggota::factory()->create(['jabatan' => Jabatan::Humas->value]);

        return User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
    }

    private function programKerjaContoh(): ProgramKerja
    {
        return ProgramKerja::create([
            'judul' => 'Program Contoh',
            'deskripsi' => 'Deskripsi contoh.',
            'status' => 'Coming Soon',
            'urutan' => 1,
        ]);
    }

    public function test_anggota_cannot_access_proker_index(): void
    {
        $this->actingAs($this->anggotaBiasa())
            ->get(route('panel.program-kerja.index'))
            ->assertForbidden();
    }

    public function test_anggota_cannot_access_proker_create(): void
    {
        $this->actingAs($this->anggotaBiasa())
            ->get(route('panel.program-kerja.create'))
            ->assertForbidden();
    }

    public function test_anggota_cannot_store_proker(): void
    {
        $this->actingAs($this->anggotaBiasa())
            ->post(route('panel.program-kerja.store'), [
                'judul' => 'Program Baru',
                'status' => 'Aktif',
                'urutan' => 1,
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('program_kerja', 0);
    }

    public function test_anggota_cannot_access_proker_edit(): void
    {
        $proker = $this->programKerjaContoh();

        $this->actingAs($this->anggotaBiasa())
            ->get(route('panel.program-kerja.edit', $proker))
            ->assertForbidden();
    }

    public function test_anggota_cannot_update_proker(): void
    {
        $proker = $this->programKerjaContoh();

        $this->actingAs($this->anggotaBiasa())
            ->put(route('panel.program-kerja.update', $proker), [
                'judul' => 'Diubah',
                'status' => 'Aktif',
                'urutan' => 1,
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('program_kerja', ['judul' => 'Program Contoh']);
    }

    public function test_anggota_cannot_destroy_proker(): void
    {
        $proker = $this->programKerjaContoh();

        $this->actingAs($this->anggotaBiasa())
            ->delete(route('panel.program-kerja.destroy', $proker))
            ->assertForbidden();

        $this->assertDatabaseCount('program_kerja', 1);
    }
}
