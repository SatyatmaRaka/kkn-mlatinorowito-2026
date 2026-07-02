<?php

namespace Tests\Feature\Panel;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KegiatanTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_kegiatan_index(): void
    {
        $this->get(route('panel.kegiatan.index'))->assertRedirect(route('login'));
    }

    public function test_kegiatan_can_be_created(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('panel.kegiatan.store'), [
                'judul' => 'Kegiatan Baru',
                'tanggal' => '2026-07-15',
                'deskripsi_singkat' => 'Deskripsi singkat.',
                'konten' => '<p>Konten aman</p>',
            ])
            ->assertRedirect(route('panel.kegiatan.index'));

        $this->assertDatabaseHas('kegiatan', ['judul' => 'Kegiatan Baru']);
    }

    public function test_kegiatan_store_strips_unsafe_html(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('panel.kegiatan.store'), [
                'judul' => 'Kegiatan XSS Test',
                'tanggal' => '2026-07-15',
                'konten' => '<p>Aman</p><script>alert(1)</script>',
            ]);

        $kegiatan = Kegiatan::first();
        $this->assertNotNull($kegiatan);
        $this->assertStringNotContainsString('<script>', $kegiatan->konten);
        $this->assertStringContainsString('<p>Aman</p>', $kegiatan->konten);
    }
}
