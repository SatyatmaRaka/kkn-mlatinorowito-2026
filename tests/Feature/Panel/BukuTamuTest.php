<?php

namespace Tests\Feature\Panel;

use App\Models\Anggota;
use App\Models\BukuTamu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BukuTamuTest extends TestCase
{
    use RefreshDatabase;

    public function test_anggota_can_create_buku_tamu(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->post(route('panel.buku-tamu.store'), [
                'tanggal' => '2026-07-01',
                'nama_tamu' => 'Bapak RT',
                'alamat_jabatan' => 'Ketua RT 01',
                'keperluan' => 'Koordinasi kegiatan',
                'anggota_id' => $anggota->id,
            ])
            ->assertRedirect(route('panel.buku-tamu.index'));

        $this->assertDatabaseHas('buku_tamu', ['nama_tamu' => 'Bapak RT']);
    }

    public function test_anggota_cannot_edit_or_delete_buku_tamu(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);
        $tamu = BukuTamu::create([
            'tanggal' => '2026-07-01',
            'nama_tamu' => 'Tamu Tes',
            'keperluan' => 'Tes',
            'dicatat_oleh' => User::factory()->create()->id,
        ]);

        $this->actingAs($user)
            ->get(route('panel.buku-tamu.edit', $tamu))
            ->assertForbidden();

        $this->actingAs($user)
            ->put(route('panel.buku-tamu.update', $tamu), [
                'tanggal' => '2026-07-02',
                'nama_tamu' => 'Diubah',
                'keperluan' => 'Ubah',
            ])
            ->assertForbidden();

        $this->actingAs($user)
            ->delete(route('panel.buku-tamu.destroy', $tamu))
            ->assertForbidden();
    }

    public function test_koordinator_can_edit_and_delete_buku_tamu(): void
    {
        $koordinator = User::factory()->koordinator()->create();
        $tamu = BukuTamu::create([
            'tanggal' => '2026-07-01',
            'nama_tamu' => 'Tamu Lama',
            'keperluan' => 'Awal',
            'dicatat_oleh' => $koordinator->id,
        ]);

        $this->actingAs($koordinator)
            ->put(route('panel.buku-tamu.update', $tamu), [
                'tanggal' => '2026-07-01',
                'nama_tamu' => 'Tamu Baru',
                'keperluan' => 'Diperbarui',
            ])
            ->assertRedirect(route('panel.buku-tamu.index'));

        $this->assertDatabaseHas('buku_tamu', ['nama_tamu' => 'Tamu Baru']);

        $this->actingAs($koordinator)
            ->delete(route('panel.buku-tamu.destroy', $tamu))
            ->assertRedirect(route('panel.buku-tamu.index'));

        $this->assertSoftDeleted('buku_tamu', ['id' => $tamu->id]);
    }

    public function test_export_csv_returns_expected_headers(): void
    {
        $admin = User::factory()->create();
        BukuTamu::create([
            'tanggal' => '2026-07-01',
            'nama_tamu' => 'Tamu Export',
            'keperluan' => 'Export tes',
            'dicatat_oleh' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('panel.buku-tamu.export'));

        $response->assertOk();
        $this->assertStringContainsString('Nama Tamu', $response->streamedContent());
        $this->assertStringContainsString('Tamu Export', $response->streamedContent());
    }

    public function test_halaman_cetak_kolom_ttd_kosong(): void
    {
        $admin = User::factory()->create();
        BukuTamu::create([
            'tanggal' => '2026-07-01',
            'nama_tamu' => 'Tamu Cetak',
            'keperluan' => 'Cetak',
            'dicatat_oleh' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('panel.buku-tamu.cetak'))
            ->assertOk()
            ->assertSee('Daftar Tamu KKN', false)
            ->assertSee('Tamu Cetak')
            ->assertSee('class="ttd"', false);
    }
}
