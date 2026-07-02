<?php

namespace Tests\Feature\Panel;

use App\Enums\PeranPengguna;
use App\Models\Anggota;
use App\Models\Pengaturan;
use App\Models\User;
use App\Layanan\LayananTokenAbsensi;
use App\Layanan\LayananPengaturan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsensiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Pengaturan::create(['key' => 'absensi_jam_mulai', 'value' => '00:00']);
        Pengaturan::create(['key' => 'absensi_jam_selesai', 'value' => '23:59']);
        LayananPengaturan::forget();
    }

    public function test_check_in_requires_valid_token(): void
    {
        $this->get(route('absensi.check-in', ['token' => 'invalid']))
            ->assertOk()
            ->assertViewIs('absensi.invalid-token');
    }

    public function test_check_in_with_valid_token_shows_login_for_guest(): void
    {
        $token = LayananTokenAbsensi::getOrCreateForToday()->token;

        $this->get(route('absensi.check-in', ['token' => $token]))
            ->assertOk()
            ->assertViewIs('absensi.login-required');
    }

    public function test_anggota_can_check_in_with_valid_token(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->create([
            'role' => PeranPengguna::Anggota,
            'anggota_id' => $anggota->id,
        ]);

        $token = LayananTokenAbsensi::getOrCreateForToday()->token;

        $this->actingAs($user)
            ->post(route('absensi.store'), ['token' => $token])
            ->assertRedirect();

        $this->assertDatabaseHas('absensi', [
            'user_id' => $user->id,
            'anggota_id' => $anggota->id,
        ]);
    }

    public function test_koordinator_can_access_rekap(): void
    {
        $user = User::factory()->koordinator()->create();

        $this->actingAs($user)
            ->get(route('panel.absensi.rekap'))
            ->assertOk();
    }

    public function test_anggota_cannot_access_rekap(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->get(route('panel.absensi.rekap'))
            ->assertForbidden();
    }

    public function test_check_in_rejected_outside_absensi_window(): void
    {
        Pengaturan::where('key', 'absensi_jam_mulai')->update(['value' => '08:00']);
        Pengaturan::where('key', 'absensi_jam_selesai')->update(['value' => '09:00']);
        LayananPengaturan::forget();

        $this->travelTo(now()->setTime(14, 0));

        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
        $token = LayananTokenAbsensi::getOrCreateForToday()->token;

        $this->actingAs($user)
            ->from(route('absensi.check-in', ['token' => $token]))
            ->post(route('absensi.store'), ['token' => $token])
            ->assertRedirect()
            ->assertSessionHasErrors('absensi');

        $this->assertDatabaseMissing('absensi', ['user_id' => $user->id]);
    }

    public function test_koordinator_with_anggota_can_check_in(): void
    {
        $user = User::factory()->koordinator()->create();
        $token = LayananTokenAbsensi::getOrCreateForToday()->token;

        $this->actingAs($user)
            ->post(route('absensi.store'), ['token' => $token])
            ->assertRedirect();

        $this->assertDatabaseHas('absensi', [
            'user_id' => $user->id,
            'anggota_id' => $user->anggota_id,
        ]);
    }
}
