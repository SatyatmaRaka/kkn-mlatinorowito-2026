<?php

namespace Tests\Feature\Panel;

use App\Models\Anggota;
use App\Models\KegiatanPelaksanaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KegiatanPelaksanaanTest extends TestCase
{
    use RefreshDatabase;

    public function test_anggota_can_create_kegiatan_but_cannot_edit_or_delete(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->post(route('panel.kegiatan-pelaksanaan.store'), [
                'nama_kegiatan' => 'Posyandu',
                'tanggal' => '2026-07-05',
                'tempat' => 'Balai Desa',
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '11:00',
            ])
            ->assertRedirect();

        $kegiatan = KegiatanPelaksanaan::first();
        $this->assertNotNull($kegiatan);

        $this->actingAs($user)
            ->get(route('panel.kegiatan-pelaksanaan.edit', $kegiatan))
            ->assertForbidden();

        $this->actingAs($user)
            ->delete(route('panel.kegiatan-pelaksanaan.destroy', $kegiatan))
            ->assertForbidden();
    }

    public function test_koordinator_can_edit_and_delete_kegiatan(): void
    {
        $koordinator = User::factory()->koordinator()->create();
        $kegiatan = KegiatanPelaksanaan::create([
            'nama_kegiatan' => 'Seminar',
            'tanggal' => '2026-07-05',
            'tempat' => 'Aula',
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '12:00',
            'dibuat_oleh' => $koordinator->id,
        ]);

        $this->actingAs($koordinator)
            ->put(route('panel.kegiatan-pelaksanaan.update', $kegiatan), [
                'nama_kegiatan' => 'Seminar Updated',
                'tanggal' => '2026-07-06',
                'tempat' => 'Aula',
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '12:00',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('kegiatan_pelaksanaan', ['nama_kegiatan' => 'Seminar Updated']);

        $this->actingAs($koordinator)
            ->delete(route('panel.kegiatan-pelaksanaan.destroy', $kegiatan))
            ->assertRedirect(route('panel.kegiatan-pelaksanaan.index'));
    }

    public function test_tambah_peserta_dan_tugas_tersimpan(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
        $kegiatan = KegiatanPelaksanaan::create([
            'nama_kegiatan' => 'Kerja Bakti',
            'tanggal' => '2026-07-05',
            'tempat' => 'Lapangan',
            'waktu_mulai' => '07:00',
            'waktu_selesai' => '10:00',
            'dibuat_oleh' => $user->id,
        ]);

        $this->actingAs($user)
            ->post(route('panel.kegiatan-pelaksanaan.peserta.store', $kegiatan), [
                'nama' => 'Warga A',
                'alamat' => 'RT 1',
            ])
            ->assertRedirect();

        $this->actingAs($user)
            ->post(route('panel.kegiatan-pelaksanaan.tugas.store', $kegiatan), [
                'anggota_id' => $anggota->id,
                'tugas' => 'Dokumentasi',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('kegiatan_peserta_masyarakat', ['nama' => 'Warga A']);
        $this->assertDatabaseHas('kegiatan_tugas_tim', ['tugas' => 'Dokumentasi']);
    }

    public function test_halaman_cetak_menampilkan_data_dan_ttd_kosong(): void
    {
        $admin = User::factory()->create();
        $kegiatan = KegiatanPelaksanaan::create([
            'nama_kegiatan' => 'Pelatihan',
            'tanggal' => '2026-07-05',
            'tempat' => 'Balai',
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '12:00',
            'dibuat_oleh' => $admin->id,
        ]);
        $kegiatan->pesertaMasyarakat()->create(['nama' => 'Peserta X', 'alamat' => 'Desa']);

        $this->actingAs($admin)
            ->get(route('panel.kegiatan-pelaksanaan.cetak-masyarakat', $kegiatan))
            ->assertOk()
            ->assertSee('Peserta X')
            ->assertSee('class="ttd"', false);
    }
}
