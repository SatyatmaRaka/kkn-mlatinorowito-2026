<?php

namespace Tests\Feature\Panel;

use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengaturanTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_pengaturan(): void
    {
        $this->get(route('panel.pengaturan.index'))->assertRedirect(route('login'));
    }

    public function test_pengaturan_website_info_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('panel.pengaturan.update'), [
                'nama_kelompok' => 'KKN Test 2026',
                'tagline' => 'Tagline baru',
                'instagram' => '@test_handle',
            ])
            ->assertRedirect(route('panel.pengaturan.index'));

        $this->assertDatabaseHas('pengaturan', [
            'key' => 'nama_kelompok',
            'value' => 'KKN Test 2026',
        ]);
    }

    public function test_pengaturan_akun_requires_current_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('panel.pengaturan.index'))
            ->put(route('panel.pengaturan.akun'), [
                'username' => 'admin_baru',
                'current_password' => 'wrong-password',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertSame($user->username, $user->fresh()->username);
    }

    public function test_pengaturan_akun_can_update_username(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('panel.pengaturan.akun'), [
                'username' => 'admin_baru',
                'current_password' => 'password',
            ])
            ->assertRedirect(route('panel.pengaturan.index'));

        $this->assertSame('admin_baru', $user->fresh()->username);
    }

    public function test_pengaturan_rejects_malicious_instagram_url(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('panel.pengaturan.index'))
            ->put(route('panel.pengaturan.update'), [
                'instagram' => 'https://evil.example/phishing',
            ])
            ->assertSessionHasErrors('instagram');
    }

    public function test_pengaturan_rejects_malicious_maps_embed_url(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('panel.pengaturan.index'))
            ->put(route('panel.pengaturan.update'), [
                'maps_embed_url' => 'https://evil.example/embed',
            ])
            ->assertSessionHasErrors('maps_embed_url');
    }
}
