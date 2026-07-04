<?php

namespace Tests\Feature\Panel;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AnggotaTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_anggota_index(): void
    {
        $this->get(route('panel.anggota.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_anggota_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('panel.anggota.index'))
            ->assertOk();
    }

    public function test_anggota_can_be_created_with_valid_data(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('panel.anggota.store'), [
                'nama' => 'Anggota Baru',
                'jurusan' => 'Sistem Informasi',
                'jabatan' => 'Humas',
                'urutan' => 1,
            ])
            ->assertRedirect(route('panel.anggota.index'))
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
            ->from(route('panel.anggota.create'))
            ->post(route('panel.anggota.store'), [
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
            ->delete(route('panel.anggota.destroy', $anggota))
            ->assertRedirect(route('panel.anggota.index'));

        $this->assertDatabaseMissing('anggota', ['id' => $anggota->id]);
    }

    public function test_anggota_with_logbook_cannot_be_deleted(): void
    {
        $anggota = Anggota::factory()->create();
        $anggotaUser = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        \App\Models\Logbook::factory()->create([
            'user_id' => $anggotaUser->id,
            'anggota_id' => $anggota->id,
        ]);

        $this->actingAs(User::factory()->create())
            ->from(route('panel.anggota.index'))
            ->delete(route('panel.anggota.destroy', $anggota))
            ->assertRedirect()
            ->assertSessionHasErrors('anggota');

        $this->assertDatabaseHas('anggota', ['id' => $anggota->id]);
    }

    public function test_admin_can_create_anggota_login_account(): void
    {
        $admin = User::factory()->create(['role' => \App\Enums\PeranPengguna::Admin]);
        $anggota = Anggota::factory()->create();

        $this->actingAs($admin)
            ->post(route('panel.anggota.akun', $anggota), [
                'username' => 'anggota_baru',
                'role' => 'anggota',
                'password' => 'PasswordKuat12',
                'password_confirmation' => 'PasswordKuat12',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'username' => 'anggota_baru',
            'anggota_id' => $anggota->id,
        ]);
    }

    public function test_sekretaris_cannot_create_anggota_login_account(): void
    {
        $sekretaris = User::factory()->sekretaris()->create();
        $anggota = Anggota::factory()->create();

        $this->actingAs($sekretaris)
            ->post(route('panel.anggota.akun', $anggota), [
                'username' => 'anggota_baru',
                'role' => 'anggota',
                'password' => 'PasswordKuat12',
                'password_confirmation' => 'PasswordKuat12',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['username' => 'anggota_baru']);
    }

    public function test_admin_can_reset_anggota_password(): void
    {
        $admin = User::factory()->create(['role' => \App\Enums\PeranPengguna::Admin]);
        $anggota = Anggota::factory()->create();
        $anggotaUser = User::factory()->anggota()->create([
            'anggota_id' => $anggota->id,
            'password' => 'PasswordLama123!',
            'wajib_ganti_password' => false,
        ]);

        $hashLama = $anggotaUser->password;

        $this->actingAs($admin)
            ->post(route('panel.anggota.reset-password', $anggota))
            ->assertRedirect()
            ->assertSessionHas('password_baru')
            ->assertSessionHas('password_baru_untuk', $anggota->nama);

        $anggotaUser->refresh();

        $this->assertTrue($anggotaUser->wajib_ganti_password);
        $this->assertFalse(Hash::check('PasswordLama123!', $anggotaUser->password));
        $this->assertNotSame($hashLama, $anggotaUser->password);
    }

    public function test_reset_password_fails_when_anggota_has_no_account(): void
    {
        $admin = User::factory()->create(['role' => \App\Enums\PeranPengguna::Admin]);
        $anggota = Anggota::factory()->create();

        $this->actingAs($admin)
            ->from(route('panel.anggota.index'))
            ->post(route('panel.anggota.reset-password', $anggota))
            ->assertRedirect()
            ->assertSessionHasErrors('anggota');
    }

    public function test_non_admin_cannot_reset_anggota_password(): void
    {
        $koordinator = User::factory()->koordinator()->create();
        $anggota = Anggota::factory()->create();
        User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($koordinator)
            ->post(route('panel.anggota.reset-password', $anggota))
            ->assertForbidden();
    }

    public function test_reset_password_invalidates_database_sessions_when_driver_is_database(): void
    {
        config(['session.driver' => 'database']);

        $admin = User::factory()->create(['role' => \App\Enums\PeranPengguna::Admin]);
        $anggota = Anggota::factory()->create();
        $anggotaUser = User::factory()->anggota()->create([
            'anggota_id' => $anggota->id,
            'password' => 'PasswordLama123!',
        ]);

        DB::table('sessions')->insert([
            'id' => 'test-session-id-anggota',
            'user_id' => $anggotaUser->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => base64_encode(serialize([])),
            'last_activity' => time(),
        ]);

        $this->actingAs($admin)
            ->post(route('panel.anggota.reset-password', $anggota))
            ->assertRedirect();

        $this->assertDatabaseMissing('sessions', [
            'id' => 'test-session-id-anggota',
            'user_id' => $anggotaUser->id,
        ]);
    }
}
