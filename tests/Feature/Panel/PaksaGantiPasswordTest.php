<?php

namespace Tests\Feature\Panel;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaksaGantiPasswordTest extends TestCase
{
    use RefreshDatabase;

    private function userWajibGantiPassword(): User
    {
        $anggota = Anggota::factory()->create();

        return User::factory()->anggota()->create([
            'anggota_id' => $anggota->id,
            'wajib_ganti_password' => true,
        ]);
    }

    public function test_user_wajib_ganti_password_redirected_from_dashboard(): void
    {
        $user = $this->userWajibGantiPassword();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('panel.pengaturan.index'))
            ->assertSessionHas(
                'warning',
                'Silakan ganti password Anda terlebih dahulu sebelum melanjutkan.'
            );
    }

    public function test_user_wajib_ganti_password_redirected_from_panel_lain(): void
    {
        $user = $this->userWajibGantiPassword();

        $this->actingAs($user)
            ->get(route('panel.catatan-harian.index'))
            ->assertRedirect(route('panel.pengaturan.index'))
            ->assertSessionHas(
                'warning',
                'Silakan ganti password Anda terlebih dahulu sebelum melanjutkan.'
            );
    }

    public function test_user_wajib_ganti_password_can_access_pengaturan(): void
    {
        $user = $this->userWajibGantiPassword();

        $this->actingAs($user)
            ->get(route('panel.pengaturan.index'))
            ->assertOk()
            ->assertSee('Ganti password wajib');
    }

    public function test_user_can_access_panel_after_password_change(): void
    {
        $user = $this->userWajibGantiPassword();

        $this->actingAs($user)
            ->put(route('panel.pengaturan.akun'), [
                'username' => $user->username,
                'current_password' => 'password',
                'password' => 'BaruMlati26!',
                'password_confirmation' => 'BaruMlati26!',
            ])
            ->assertRedirect(route('panel.pengaturan.index'));

        $user->refresh();
        $this->assertFalse($user->wajib_ganti_password);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('panel.catatan-harian.index'))
            ->assertOk();
    }
}
