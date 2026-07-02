<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\Anggota;
use App\Models\Pengaturan;
use App\Models\User;
use App\Services\AbsensiTokenService;
use App\Services\PengaturanService;
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
        PengaturanService::forget();
    }

    public function test_check_in_requires_valid_token(): void
    {
        $this->get(route('absensi.check-in', ['token' => 'invalid']))
            ->assertOk()
            ->assertViewIs('absensi.invalid-token');
    }

    public function test_check_in_with_valid_token_shows_login_for_guest(): void
    {
        $token = AbsensiTokenService::getOrCreateForToday()->token;

        $this->get(route('absensi.check-in', ['token' => $token]))
            ->assertOk()
            ->assertViewIs('absensi.login-required');
    }

    public function test_anggota_can_check_in_with_valid_token(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->create([
            'role' => UserRole::Anggota,
            'anggota_id' => $anggota->id,
        ]);

        $token = AbsensiTokenService::getOrCreateForToday()->token;

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
            ->get(route('admin.absensi.rekap'))
            ->assertOk();
    }

    public function test_anggota_cannot_access_rekap(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->get(route('admin.absensi.rekap'))
            ->assertForbidden();
    }
}
