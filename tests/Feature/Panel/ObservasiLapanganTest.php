<?php

namespace Tests\Feature\Panel;

use App\Models\Anggota;
use App\Models\ObservasiLapangan;
use App\Models\ObservasiLapanganItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObservasiLapanganTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_login_dapat_akses_index(): void
    {
        $user = User::factory()->anggota()->create();

        $this->actingAs($user)
            ->get(route('panel.observasi-lapangan.index'))
            ->assertOk()
            ->assertSee('Observasi Lapangan');
    }

    public function test_guest_di_redirect_ke_login(): void
    {
        $this->get(route('panel.observasi-lapangan.index'))
            ->assertRedirect(route('login'));
    }

    public function test_update_berhasil_menyimpan_data_item_dan_narasi(): void
    {
        $user = User::factory()->anggota()->create();
        $observasi = ObservasiLapangan::ambilOrBuatDefault();
        $item = $observasi->items->first();

        $payload = [
            'ringkasan_permasalahan' => 'Permasalahan utama desa',
            'rencana_pemecahan' => 'Rencana TTG desa',
            'items' => $observasi->items->map(fn (ObservasiLapanganItem $i) => [
                'id' => $i->id,
                'status' => $i->id === $item->id ? 'ada' : 'tidak',
                'permasalahan' => $i->id === $item->id ? 'Kurang tenaga' : null,
                'rencana_pemecahan_masalah' => $i->id === $item->id ? 'Rekrut relawan' : null,
            ])->all(),
        ];

        $this->actingAs($user)
            ->put(route('panel.observasi-lapangan.update'), $payload)
            ->assertRedirect(route('panel.observasi-lapangan.index'));

        $observasi->refresh();
        $item->refresh();

        $this->assertSame('Permasalahan utama desa', $observasi->ringkasan_permasalahan);
        $this->assertSame('Rencana TTG desa', $observasi->rencana_pemecahan);
        $this->assertSame('ada', $item->status);
        $this->assertSame('Kurang tenaga', $item->permasalahan);
        $this->assertSame('Rekrut relawan', $item->rencana_pemecahan_masalah);
    }

    public function test_tidak_bisa_hapus_item_kelembagaan_wajib(): void
    {
        $koordinator = User::factory()->koordinator()->create();
        $observasi = ObservasiLapangan::ambilOrBuatDefault();
        $itemWajib = $observasi->items->first();

        $this->actingAs($koordinator)
            ->delete(route('panel.observasi-lapangan.item.destroy', $itemWajib))
            ->assertForbidden();

        $this->assertDatabaseHas('observasi_lapangan_item', ['id' => $itemWajib->id]);
    }

    public function test_anggota_tidak_bisa_hapus_item_tambahan(): void
    {
        $anggota = User::factory()->anggota()->create();
        $observasi = ObservasiLapangan::ambilOrBuatDefault();
        $itemTambahan = $observasi->items()->create([
            'nama_kelembagaan' => 'LPM Desa',
            'status' => 'tidak',
            'urutan' => 99,
        ]);

        $this->actingAs($anggota)
            ->delete(route('panel.observasi-lapangan.item.destroy', $itemTambahan))
            ->assertForbidden();
    }

    public function test_koordinator_bisa_tambah_dan_hapus_item_tambahan(): void
    {
        $koordinator = User::factory()->koordinator()->create();
        ObservasiLapangan::ambilOrBuatDefault();

        $this->actingAs($koordinator)
            ->post(route('panel.observasi-lapangan.item.store'), [
                'nama_kelembagaan' => 'LPM Desa',
            ])
            ->assertRedirect();

        $item = ObservasiLapanganItem::where('nama_kelembagaan', 'LPM Desa')->first();
        $this->assertNotNull($item);

        $this->actingAs($koordinator)
            ->delete(route('panel.observasi-lapangan.item.destroy', $item))
            ->assertRedirect();

        $this->assertDatabaseMissing('observasi_lapangan_item', ['id' => $item->id]);
    }

    public function test_ambil_or_buat_default_membuat_11_item_tanpa_duplikat(): void
    {
        $pertama = ObservasiLapangan::ambilOrBuatDefault();
        $this->assertCount(11, $pertama->items);
        $this->assertDatabaseCount('observasi_lapangan', 1);
        $this->assertDatabaseCount('observasi_lapangan_item', 11);

        $kedua = ObservasiLapangan::ambilOrBuatDefault();
        $this->assertSame($pertama->id, $kedua->id);
        $this->assertCount(11, $kedua->items);
        $this->assertDatabaseCount('observasi_lapangan', 1);
        $this->assertDatabaseCount('observasi_lapangan_item', 11);
    }

    public function test_halaman_cetak_observasi_lapangan_dapat_diakses(): void
    {
        $user = User::factory()->create();
        ObservasiLapangan::ambilOrBuatDefault();

        $this->actingAs($user)
            ->get(route('panel.observasi-lapangan.cetak'))
            ->assertOk()
            ->assertSee('Observasi Lapangan')
            ->assertSee('Posyandu');
    }
}
