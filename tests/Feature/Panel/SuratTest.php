<?php

namespace Tests\Feature\Panel;

use App\Models\Anggota;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuratTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_surat_index(): void
    {
        $this->get(route('panel.surat.index'))->assertRedirect(route('login'));
    }

    public function test_sekretaris_can_access_surat_index(): void
    {
        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->get(route('panel.surat.index'))
            ->assertOk()
            ->assertSee('Surat Menyurat');
    }

    public function test_anggota_without_jabatan_cms_cannot_access_surat(): void
    {
        $anggota = Anggota::factory()->create(['jabatan' => 'Humas']);
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->get(route('panel.surat.index'))
            ->assertForbidden();
    }

    public function test_sekretaris_can_access_create_surat_keluar(): void
    {
        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->get(route('panel.surat.create', ['jenis' => 'keluar', 'kategori' => 'rt']))
            ->assertOk()
            ->assertSee('Buat Surat Keluar')
            ->assertSee('Nomor RT');
    }

    public function test_sekretaris_can_print_surat_keluar(): void
    {
        $user = User::factory()->sekretaris()->create();
        $surat = Surat::factory()->keluar()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('panel.surat.cetak', $surat))
            ->assertOk()
            ->assertSee($surat->perihal);
    }

    public function test_cetak_only_for_surat_keluar(): void
    {
        $user = User::factory()->sekretaris()->create();
        $surat = Surat::factory()->masuk()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('panel.surat.cetak', $surat))
            ->assertNotFound();
    }

    public function test_sekretaris_can_create_surat_keluar_kelurahan_with_pdf(): void
    {
        Storage::fake('public');

        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->post(route('panel.surat.store'), [
                'jenis' => 'keluar',
                'kategori_tujuan' => 'kelurahan',
                'tanggal' => now()->toDateString(),
                'perihal' => 'Undangan rapat koordinasi',
                'keterangan' => 'Melalui surat ini kami undang Bapak/Ibu untuk hadir.',
            ])
            ->assertRedirect();

        $surat = Surat::first();
        $this->assertSame('keluar', $surat->jenis);
        $this->assertSame('kelurahan', $surat->kategori_tujuan);
        $this->assertSame('Lurah Kelurahan Mlatinorowito', $surat->asal_tujuan);
        $this->assertNotEmpty($surat->nomor_surat);
        $this->assertNotEmpty($surat->lampiran);
        Storage::disk('public')->assertExists($surat->lampiran);
    }

    public function test_sekretaris_can_create_surat_keluar_rt(): void
    {
        Storage::fake('public');

        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->post(route('panel.surat.store'), [
                'jenis' => 'keluar',
                'kategori_tujuan' => 'rt',
                'nomor_rt' => '03',
                'nomor_rw' => '05',
                'tanggal' => now()->toDateString(),
                'perihal' => 'Undangan warga RT',
                'keterangan' => 'Isi undangan kegiatan KKN di RT.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('surat', [
            'kategori_tujuan' => 'rt',
            'nomor_rt' => '03',
            'nomor_rw' => '05',
            'asal_tujuan' => 'Ketua RT 03 RW 05 Kelurahan Mlatinorowito',
        ]);
    }

    public function test_sekretaris_can_create_surat_keluar_rw(): void
    {
        Storage::fake('public');

        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->post(route('panel.surat.store'), [
                'jenis' => 'keluar',
                'kategori_tujuan' => 'rw',
                'nomor_rw' => '02',
                'tanggal' => now()->toDateString(),
                'perihal' => 'Koordinasi kegiatan RW',
                'keterangan' => 'Isi surat koordinasi ke RW.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('surat', [
            'kategori_tujuan' => 'rw',
            'asal_tujuan' => 'Ketua RW 02 Kelurahan Mlatinorowito',
        ]);
    }

    public function test_sekretaris_can_download_surat_keluar_pdf(): void
    {
        Storage::fake('public');

        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->post(route('panel.surat.store'), [
                'jenis' => 'keluar',
                'kategori_tujuan' => 'instansi',
                'asal_tujuan' => 'Dinas Pendidikan Kudus',
                'tanggal' => now()->toDateString(),
                'perihal' => 'Permohonan izin kegiatan',
                'keterangan' => 'Isi surat permohonan kegiatan KKN.',
            ]);

        $surat = Surat::first();

        $this->actingAs($user)
            ->get(route('panel.surat.unduh', $surat))
            ->assertOk();
    }

    public function test_sekretaris_can_create_surat_masuk(): void
    {
        Storage::fake('public');

        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->post(route('panel.surat.store'), [
                'jenis' => 'masuk',
                'nomor_surat' => '001/KKN/2026',
                'tanggal' => now()->toDateString(),
                'asal_tujuan' => 'Kelurahan Mlatinorowito',
                'perihal' => 'Undangan rapat koordinasi',
                'keterangan' => 'Surat undangan kegiatan KKN',
            ])
            ->assertRedirect(route('panel.surat.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('surat', [
            'jenis' => 'masuk',
            'perihal' => 'Undangan rapat koordinasi',
            'user_id' => $user->id,
        ]);
    }

    public function test_sekretaris_can_update_and_delete_surat(): void
    {
        $user = User::factory()->sekretaris()->create();
        $surat = Surat::factory()->masuk()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->put(route('panel.surat.update', $surat), [
                'jenis' => 'masuk',
                'nomor_surat' => '002/KKN/2026',
                'tanggal' => now()->toDateString(),
                'asal_tujuan' => 'Dinas Pendidikan Kudus',
                'perihal' => 'Balasan surat masuk',
                'keterangan' => 'Catatan arsip surat masuk.',
            ])
            ->assertRedirect(route('panel.surat.index'));

        $this->assertDatabaseHas('surat', [
            'id' => $surat->id,
            'jenis' => 'masuk',
            'perihal' => 'Balasan surat masuk',
        ]);

        $this->actingAs($user)
            ->delete(route('panel.surat.destroy', $surat))
            ->assertRedirect(route('panel.surat.index'));

        $this->assertSoftDeleted('surat', ['id' => $surat->id]);
    }

    public function test_surat_store_rejects_invalid_jenis(): void
    {
        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->from(route('panel.surat.create'))
            ->post(route('panel.surat.store'), [
                'jenis' => 'invalid',
                'tanggal' => now()->toDateString(),
                'asal_tujuan' => 'Test',
                'perihal' => 'Test',
            ])
            ->assertSessionHasErrors('jenis');
    }

    public function test_surat_keluar_rt_requires_nomor_rt(): void
    {
        $user = User::factory()->sekretaris()->create();

        $this->actingAs($user)
            ->from(route('panel.surat.create', ['jenis' => 'keluar', 'kategori' => 'rt']))
            ->post(route('panel.surat.store'), [
                'jenis' => 'keluar',
                'kategori_tujuan' => 'rt',
                'tanggal' => now()->toDateString(),
                'perihal' => 'Test',
                'keterangan' => 'Isi surat.',
            ])
            ->assertSessionHasErrors('nomor_rt');
    }
}
