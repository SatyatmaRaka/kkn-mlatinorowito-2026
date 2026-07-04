<?php

namespace Tests\Feature\Panel;

use App\Models\Anggota;
use App\Models\Logbook;
use App\Models\User;
use App\Notifications\NotifikasiLogbookDikirim;
use App\Notifications\NotifikasiLogbookDireview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LogbookNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_anggota_receives_notification_when_logbook_reviewed(): void
    {
        Notification::fake();

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

        Notification::assertSentTo($anggotaUser, NotifikasiLogbookDireview::class);
    }

    public function test_reviewer_receives_notification_when_logbook_submitted(): void
    {
        Notification::fake();

        $anggota = Anggota::factory()->create();
        $anggotaUser = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
        $koordinator = User::factory()->koordinator()->create();

        $this->actingAs($anggotaUser)
            ->post(route('panel.catatan-harian.store'), [
                'tanggal' => now()->toDateString(),
                'judul' => 'Logbook Baru',
                'deskripsi' => 'Kegiatan hari ini cukup panjang untuk validasi.',
                'submit' => 1,
            ])
            ->assertRedirect();

        Notification::assertSentTo($koordinator, NotifikasiLogbookDikirim::class);
    }

    public function test_wakil_koordinator_by_jabatan_receives_notification_when_logbook_submitted(): void
    {
        Notification::fake();

        $anggota = Anggota::factory()->create();
        $anggotaUser = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
        $wakilKoordinator = User::factory()->wakilKoordinator()->create();

        $this->actingAs($anggotaUser)
            ->post(route('panel.catatan-harian.store'), [
                'tanggal' => now()->toDateString(),
                'judul' => 'Logbook Baru',
                'deskripsi' => 'Kegiatan hari ini cukup panjang untuk validasi.',
                'submit' => 1,
            ])
            ->assertRedirect();

        Notification::assertSentTo($wakilKoordinator, NotifikasiLogbookDikirim::class);
    }
}
