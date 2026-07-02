<?php

namespace Tests\Feature\Panel;

use App\Models\ProgramKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProkerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_proker_index(): void
    {
        $this->get(route('panel.program-kerja.index'))->assertRedirect(route('login'));
    }

    public function test_proker_can_be_created(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('panel.program-kerja.store'), [
                'judul' => 'Program Baru',
                'deskripsi' => 'Deskripsi program.',
                'status' => 'Coming Soon',
                'urutan' => 1,
            ])
            ->assertRedirect(route('panel.program-kerja.index'));

        $this->assertDatabaseHas('program_kerja', ['judul' => 'Program Baru']);
    }

    public function test_proker_store_rejects_invalid_urutan(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('panel.program-kerja.create'))
            ->post(route('panel.program-kerja.store'), [
                'judul' => 'Program Baru',
                'status' => 'Aktif',
                'urutan' => 0,
            ])
            ->assertSessionHasErrors('urutan');

        $this->assertDatabaseCount('program_kerja', 0);
    }
}
