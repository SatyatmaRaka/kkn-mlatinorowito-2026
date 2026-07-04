<?php

namespace Tests\Feature\Panel;

use App\Enums\PeranPengguna;
use App\Models\Anggota;
use App\Models\Logbook;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogbookTest extends TestCase
{
    use RefreshDatabase;

    public function test_anggota_can_create_logbook(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->post(route('panel.catatan-harian.store'), [
                'tanggal' => '2026-07-01',
                'judul' => 'Kegiatan Posyandu',
                'deskripsi' => 'Membantu posyandu balita di RW 3.',
            ])
            ->assertRedirect(route('panel.catatan-harian.index'));

        $this->assertDatabaseHas('logbooks', [
            'user_id' => $user->id,
            'judul' => 'Kegiatan Posyandu',
            'status' => Logbook::STATUS_DRAFT,
        ]);
    }

    public function test_koordinator_can_review_submitted_logbook(): void
    {
        $anggota = Anggota::factory()->create();
        $anggotaUser = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
        $koordinator = User::factory()->koordinator()->create();

        $logbook = Logbook::factory()->submitted()->create([
            'user_id' => $anggotaUser->id,
            'anggota_id' => $anggota->id,
        ]);

        $this->actingAs($koordinator)
            ->patch(route('panel.catatan-harian.review', $logbook), [
                'status' => 'approved',
            ])
            ->assertRedirect();

        $this->assertSame(Logbook::STATUS_APPROVED, $logbook->fresh()->status);
    }

    public function test_koordinator_cannot_review_approved_logbook(): void
    {
        $anggota = Anggota::factory()->create();
        $anggotaUser = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
        $koordinator = User::factory()->koordinator()->create();

        $logbook = Logbook::factory()->approved()->create([
            'user_id' => $anggotaUser->id,
            'anggota_id' => $anggota->id,
        ]);

        $this->actingAs($koordinator)
            ->from(route('panel.catatan-harian.index'))
            ->patch(route('panel.catatan-harian.review', $logbook), [
                'status' => 'rejected',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors('status');

        $this->assertSame(Logbook::STATUS_APPROVED, $logbook->fresh()->status);
    }

    public function test_anggota_cannot_edit_approved_logbook(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $logbook = Logbook::factory()->approved()->create([
            'user_id' => $user->id,
            'anggota_id' => $anggota->id,
        ]);

        $this->actingAs($user)
            ->get(route('panel.catatan-harian.edit', $logbook))
            ->assertForbidden();
    }

    public function test_koordinator_cannot_edit_submitted_logbook_of_anggota(): void
    {
        $anggota = Anggota::factory()->create();
        $anggotaUser = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
        $koordinator = User::factory()->koordinator()->create();

        $logbook = Logbook::factory()->submitted()->create([
            'user_id' => $anggotaUser->id,
            'anggota_id' => $anggota->id,
        ]);

        $this->actingAs($koordinator)
            ->get(route('panel.catatan-harian.edit', $logbook))
            ->assertForbidden();

        $this->actingAs($koordinator)
            ->put(route('panel.catatan-harian.update', $logbook), [
                'tanggal' => '2026-07-01',
                'judul' => 'Diubah koordinator',
                'deskripsi' => 'Seharusnya ditolak.',
            ])
            ->assertForbidden();
    }

    public function test_admin_cannot_edit_submitted_logbook_of_anggota(): void
    {
        $anggota = Anggota::factory()->create();
        $anggotaUser = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
        $admin = User::factory()->create(['role' => PeranPengguna::Admin]);

        $logbook = Logbook::factory()->submitted()->create([
            'user_id' => $anggotaUser->id,
            'anggota_id' => $anggota->id,
        ]);

        $this->actingAs($admin)
            ->get(route('panel.catatan-harian.edit', $logbook))
            ->assertForbidden();
    }

    public function test_anggota_can_edit_rejected_own_logbook(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $logbook = Logbook::factory()->create([
            'user_id' => $user->id,
            'anggota_id' => $anggota->id,
            'status' => Logbook::STATUS_REJECTED,
        ]);

        $this->actingAs($user)
            ->get(route('panel.catatan-harian.edit', $logbook))
            ->assertOk();
    }
}
