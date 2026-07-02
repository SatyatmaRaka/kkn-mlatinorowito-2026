<?php

namespace Tests\Feature\Panel;

use App\Models\Anggota;
use App\Models\Logbook;
use App\Models\User;
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
}
