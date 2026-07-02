<?php

namespace Tests\Feature\Admin;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnggotaTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_anggota_index(): void
    {
        $this->get(route('admin.anggota.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_anggota_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.anggota.index'))
            ->assertOk();
    }

    public function test_anggota_can_be_created_with_valid_data(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.anggota.store'), [
                'nama' => 'Anggota Baru',
                'jurusan' => 'Sistem Informasi',
                'jabatan' => 'Humas',
                'urutan' => 1,
            ])
            ->assertRedirect(route('admin.anggota.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('anggota', [
            'nama' => 'Anggota Baru',
            'jabatan' => 'Humas',
        ]);
    }

    public function test_anggota_store_rejects_invalid_jabatan(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('admin.anggota.create'))
            ->post(route('admin.anggota.store'), [
                'nama' => 'Anggota Baru',
                'jurusan' => 'Sistem Informasi',
                'jabatan' => 'Jabatan Tidak Valid',
                'urutan' => 1,
            ])
            ->assertSessionHasErrors('jabatan');

        $this->assertDatabaseCount('anggota', 0);
    }

    public function test_anggota_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $anggota = Anggota::create([
            'nama' => 'Hapus Saya',
            'jurusan' => 'Manajemen',
            'jabatan' => 'PDD',
            'urutan' => 1,
        ]);

        $this->actingAs($user)
            ->delete(route('admin.anggota.destroy', $anggota))
            ->assertRedirect(route('admin.anggota.index'));

        $this->assertDatabaseMissing('anggota', ['id' => $anggota->id]);
    }
}
