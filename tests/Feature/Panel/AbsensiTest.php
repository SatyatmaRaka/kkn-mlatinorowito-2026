<?php

namespace Tests\Feature\Panel;

use App\Enums\PeranPengguna;
use App\Models\Absensi;
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
            'status' => Absensi::STATUS_HADIR,
            'metode' => 'qr',
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
            'status' => Absensi::STATUS_HADIR,
        ]);
    }

    public function test_koordinator_can_record_izin_for_anggota(): void
    {
        $koordinator = User::factory()->koordinator()->create();
        $anggota = Anggota::factory()->create();
        $anggotaUser = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
        $tanggal = now()->toDateString();

        $this->actingAs($koordinator)
            ->post(route('panel.absensi.catat-izin-sakit'), [
                'anggota_id' => $anggota->id,
                'tanggal' => $tanggal,
                'status' => Absensi::STATUS_IZIN,
                'keterangan' => 'Keperluan keluarga mendesak',
            ])
            ->assertRedirect(route('panel.absensi.rekap', ['tanggal' => $tanggal]))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('absensi', [
            'user_id' => $anggotaUser->id,
            'anggota_id' => $anggota->id,
            'status' => Absensi::STATUS_IZIN,
            'keterangan' => 'Keperluan keluarga mendesak',
            'dicatat_oleh' => $koordinator->id,
            'metode' => 'manual',
            'check_in_at' => null,
        ]);
    }

    public function test_koordinator_can_record_sakit_for_anggota(): void
    {
        $admin = User::factory()->create();
        $anggota = Anggota::factory()->create();
        User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
        $tanggal = now()->toDateString();

        $this->actingAs($admin)
            ->post(route('panel.absensi.catat-izin-sakit'), [
                'anggota_id' => $anggota->id,
                'tanggal' => $tanggal,
                'status' => Absensi::STATUS_SAKIT,
                'keterangan' => 'Demam tinggi',
            ])
            ->assertRedirect(route('panel.absensi.rekap', ['tanggal' => $tanggal]))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('absensi', [
            'anggota_id' => $anggota->id,
            'status' => Absensi::STATUS_SAKIT,
            'keterangan' => 'Demam tinggi',
            'dicatat_oleh' => $admin->id,
            'metode' => 'manual',
        ]);
    }

    public function test_cannot_record_izin_sakit_when_record_exists_for_date(): void
    {
        $koordinator = User::factory()->koordinator()->create();
        $anggota = Anggota::factory()->create();
        $anggotaUser = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
        $tanggal = now()->toDateString();

        Absensi::create([
            'user_id' => $anggotaUser->id,
            'anggota_id' => $anggota->id,
            'tanggal' => $tanggal,
            'status' => Absensi::STATUS_HADIR,
            'check_in_at' => now(),
            'metode' => 'qr',
        ]);

        $this->actingAs($koordinator)
            ->from(route('panel.absensi.rekap'))
            ->post(route('panel.absensi.catat-izin-sakit'), [
                'anggota_id' => $anggota->id,
                'tanggal' => $tanggal,
                'status' => Absensi::STATUS_IZIN,
                'keterangan' => 'Coba duplikat',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors('absensi');

        $this->assertDatabaseCount('absensi', 1);
    }

    public function test_anggota_cannot_access_catat_izin_sakit_endpoint(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
        $target = Anggota::factory()->create();
        User::factory()->anggota()->create(['anggota_id' => $target->id]);

        $this->actingAs($user)
            ->post(route('panel.absensi.catat-izin-sakit'), [
                'anggota_id' => $target->id,
                'tanggal' => now()->toDateString(),
                'status' => Absensi::STATUS_IZIN,
                'keterangan' => 'Tidak boleh',
            ])
            ->assertForbidden();
    }

    public function test_manual_record_can_be_deleted_but_qr_record_cannot(): void
    {
        $koordinator = User::factory()->koordinator()->create();
        $anggota = Anggota::factory()->create();
        $anggotaUser = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
        $tanggal = now()->toDateString();

        $manual = Absensi::create([
            'user_id' => $anggotaUser->id,
            'anggota_id' => $anggota->id,
            'tanggal' => $tanggal,
            'status' => Absensi::STATUS_IZIN,
            'keterangan' => 'Salah input',
            'dicatat_oleh' => $koordinator->id,
            'metode' => 'manual',
        ]);

        $this->actingAs($koordinator)
            ->delete(route('panel.absensi.hapus-catatan', $manual))
            ->assertRedirect(route('panel.absensi.rekap', ['tanggal' => $tanggal]));

        $this->assertDatabaseMissing('absensi', ['id' => $manual->id]);

        $qr = Absensi::create([
            'user_id' => $anggotaUser->id,
            'anggota_id' => $anggota->id,
            'tanggal' => $tanggal,
            'status' => Absensi::STATUS_HADIR,
            'check_in_at' => now(),
            'metode' => 'qr',
        ]);

        $this->actingAs($koordinator)
            ->delete(route('panel.absensi.hapus-catatan', $qr))
            ->assertForbidden();
    }

    public function test_rekap_groups_hadir_izin_sakit_and_belum_correctly(): void
    {
        $admin = User::factory()->create();
        $tanggal = now()->toDateString();

        $hadirAnggota = Anggota::factory()->create(['nama' => 'Hadir Satu', 'urutan' => 1]);
        $hadirUser = User::factory()->anggota()->create(['anggota_id' => $hadirAnggota->id]);
        Absensi::create([
            'user_id' => $hadirUser->id,
            'anggota_id' => $hadirAnggota->id,
            'tanggal' => $tanggal,
            'status' => Absensi::STATUS_HADIR,
            'check_in_at' => now(),
            'metode' => 'qr',
        ]);

        $izinAnggota = Anggota::factory()->create(['nama' => 'Izin Dua', 'urutan' => 2]);
        $izinUser = User::factory()->anggota()->create(['anggota_id' => $izinAnggota->id]);
        Absensi::create([
            'user_id' => $izinUser->id,
            'anggota_id' => $izinAnggota->id,
            'tanggal' => $tanggal,
            'status' => Absensi::STATUS_IZIN,
            'keterangan' => 'Acara kampus',
            'dicatat_oleh' => $admin->id,
            'metode' => 'manual',
        ]);

        $sakitAnggota = Anggota::factory()->create(['nama' => 'Sakit Tiga', 'urutan' => 3]);
        $sakitUser = User::factory()->anggota()->create(['anggota_id' => $sakitAnggota->id]);
        Absensi::create([
            'user_id' => $sakitUser->id,
            'anggota_id' => $sakitAnggota->id,
            'tanggal' => $tanggal,
            'status' => Absensi::STATUS_SAKIT,
            'keterangan' => 'Flu',
            'dicatat_oleh' => $admin->id,
            'metode' => 'manual',
        ]);

        $belumAnggota = Anggota::factory()->create(['nama' => 'Belum Empat', 'urutan' => 4]);
        User::factory()->anggota()->create(['anggota_id' => $belumAnggota->id]);

        $response = $this->actingAs($admin)
            ->get(route('panel.absensi.rekap', ['tanggal' => $tanggal]));

        $response->assertOk()
            ->assertViewHas('hadir', fn ($c) => $c->count() === 1 && $c->first()->nama === 'Hadir Satu')
            ->assertViewHas('izin', fn ($c) => $c->count() === 1 && $c->first()->nama === 'Izin Dua')
            ->assertViewHas('sakit', fn ($c) => $c->count() === 1 && $c->first()->nama === 'Sakit Tiga')
            ->assertViewHas('belum', fn ($c) => $c->count() === 1 && $c->first()->nama === 'Belum Empat');
    }
}
