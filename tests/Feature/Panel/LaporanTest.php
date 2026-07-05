<?php

namespace Tests\Feature\Panel;

use App\Enums\Jabatan;
use App\Enums\PeranPengguna;
use App\Models\Absensi;
use App\Models\Anggota;
use App\Models\Keuangan;
use App\Models\Pengaturan;
use App\Models\User;
use App\Layanan\LayananPengaturan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedKeuanganContoh();
        $this->seedPeriodeKkn();
    }

    private function seedPeriodeKkn(): void
    {
        Pengaturan::updateOrCreate(['key' => 'tanggal_mulai_kkn'], ['value' => '2026-07-01']);
        Pengaturan::updateOrCreate(['key' => 'tanggal_selesai_kkn'], ['value' => '2026-07-14']);
        Pengaturan::updateOrCreate(['key' => 'desa'], ['value' => 'Mlatinorowito']);
        Pengaturan::updateOrCreate(['key' => 'kecamatan'], ['value' => 'Kota']);
        Pengaturan::updateOrCreate(['key' => 'kabupaten'], ['value' => 'Kudus']);
        Pengaturan::updateOrCreate(['key' => 'nama_dpl'], ['value' => 'Dr. Contoh DPL']);
        Pengaturan::updateOrCreate(['key' => 'nidn_dpl'], ['value' => '1234567890']);

        LayananPengaturan::forget();
    }

    private function seedKeuanganContoh(): void
    {
        $user = User::factory()->create();

        Keuangan::create([
            'user_id' => $user->id,
            'diubah_oleh' => $user->id,
            'tanggal' => now()->toDateString(),
            'jenis' => 'pemasukan',
            'keterangan' => 'Dana contoh laporan',
            'nominal' => 1_500_000,
        ]);
    }

    public function test_admin_can_view_laporan(): void
    {
        $admin = User::factory()->create(['role' => PeranPengguna::Admin]);

        $this->actingAs($admin)
            ->get(route('panel.laporan.index'))
            ->assertOk()
            ->assertSee('Laporan KKN')
            ->assertSee('1.500.000')
            ->assertSee('Saldo periode');
    }

    public function test_anggota_cannot_view_laporan(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->get(route('panel.laporan.index'))
            ->assertForbidden();
    }

    public function test_wakil_koordinator_can_view_laporan_without_keuangan(): void
    {
        $wakil = User::factory()->wakilKoordinator()->create();

        $this->actingAs($wakil)
            ->get(route('panel.laporan.index'))
            ->assertOk()
            ->assertSee('Absensi')
            ->assertSee('Logbook')
            ->assertDontSee('Saldo periode')
            ->assertDontSee('1.500.000')
            ->assertDontSee('Export CSV Keuangan');
    }

    public function test_bendahara_sees_keuangan_in_laporan(): void
    {
        $bendahara = User::factory()->bendahara()->create();

        $this->actingAs($bendahara)
            ->get(route('panel.laporan.keuangan'))
            ->assertOk()
            ->assertSee('1.500.000')
            ->assertSee('Saldo periode');
    }

    public function test_koordinator_sees_keuangan_in_laporan(): void
    {
        $koordinator = User::factory()->koordinator()->create();

        $this->actingAs($koordinator)
            ->get(route('panel.laporan.index'))
            ->assertOk()
            ->assertSee('1.500.000')
            ->assertSee('Saldo periode');
    }

    public function test_wakil_koordinator_export_excludes_keuangan_rows(): void
    {
        $wakil = User::factory()->wakilKoordinator()->create();

        $response = $this->actingAs($wakil)
            ->get(route('panel.laporan.export'));

        $response->assertOk();
        $this->assertStringNotContainsString('Pemasukan (Rp)', $response->streamedContent());
        $this->assertStringNotContainsString('1500000', $response->streamedContent());
    }

    public function test_admin_export_includes_keuangan_rows(): void
    {
        $admin = User::factory()->create(['role' => PeranPengguna::Admin]);

        $response = $this->actingAs($admin)
            ->get(route('panel.laporan.export'));

        $response->assertOk();
        $this->assertStringContainsString('Pemasukan (Rp)', $response->streamedContent());
        $this->assertStringContainsString('1500000', $response->streamedContent());
    }

    public function test_live_dasbor_api_returns_json(): void
    {
        $admin = User::factory()->create(['role' => PeranPengguna::Admin]);

        $this->actingAs($admin)
            ->getJson(route('panel.api.live.dasbor'))
            ->assertOk()
            ->assertJsonStructure(['waktu', 'logbook_menunggu', 'absensi_hari_ini', 'saldo_bulan_ini']);
    }

    public function test_live_dasbor_api_hides_saldo_for_wakil_koordinator(): void
    {
        $wakil = User::factory()->wakilKoordinator()->create();

        $this->actingAs($wakil)
            ->getJson(route('panel.api.live.dasbor'))
            ->assertOk()
            ->assertJsonMissing(['saldo_bulan_ini']);
    }

    public function test_koordinator_can_view_daftar_hadir_mingguan(): void
    {
        $koordinator = User::factory()->koordinator()->create();

        $this->actingAs($koordinator)
            ->get(route('panel.laporan.daftar-hadir-mingguan'))
            ->assertOk()
            ->assertSee('Daftar Hadir Harian Tim KKN', false)
            ->assertSee('Mlatinorowito');
    }

    public function test_daftar_hadir_mingguan_menampilkan_simbol_absensi(): void
    {
        Anggota::factory()->create([
            'nama' => 'Kordes Tes',
            'nim' => '1111111111',
            'jabatan' => Jabatan::KoordinatorDesa->value,
            'urutan' => 1,
        ]);

        $hadir = Anggota::factory()->create(['nama' => 'Anggota Hadir', 'nim' => '2222222222', 'urutan' => 2]);
        $izin = Anggota::factory()->create(['nama' => 'Anggota Izin', 'nim' => '3333333333', 'urutan' => 3]);
        $kosong = Anggota::factory()->create(['nama' => 'Anggota Kosong', 'nim' => '4444444444', 'urutan' => 4]);

        $hadirUser = User::factory()->anggota()->create(['anggota_id' => $hadir->id]);
        $izinUser = User::factory()->anggota()->create(['anggota_id' => $izin->id]);
        User::factory()->anggota()->create(['anggota_id' => $kosong->id]);

        Absensi::create([
            'user_id' => $hadirUser->id,
            'anggota_id' => $hadir->id,
            'tanggal' => '2026-07-01',
            'status' => Absensi::STATUS_HADIR,
            'check_in_at' => now(),
            'metode' => 'qr',
        ]);

        Absensi::create([
            'user_id' => $izinUser->id,
            'anggota_id' => $izin->id,
            'tanggal' => '2026-07-02',
            'status' => Absensi::STATUS_IZIN,
            'keterangan' => 'Acara keluarga',
            'dicatat_oleh' => User::factory()->koordinator()->create()->id,
            'metode' => 'manual',
        ]);

        $admin = User::factory()->create(['role' => PeranPengguna::Admin]);

        $response = $this->actingAs($admin)
            ->get(route('panel.laporan.daftar-hadir-mingguan', ['minggu' => 1]));

        $response->assertOk()
            ->assertSee('Anggota Hadir')
            ->assertSee('Anggota Izin')
            ->assertSee('Anggota Kosong')
            ->assertSee('02/07: Acara keluarga');

        $html = $response->getContent();
        $this->assertMatchesRegularExpression('/Anggota Hadir[\s\S]*?<td class="simbol">H<\/td>/', $html);
        $this->assertMatchesRegularExpression('/Anggota Izin[\s\S]*?<td class="simbol">I<\/td>/', $html);
        $this->assertMatchesRegularExpression('/Anggota Kosong[\s\S]*?<td class="simbol">-<\/td>/', $html);
    }

    public function test_anggota_cannot_view_daftar_hadir_mingguan(): void
    {
        $anggota = Anggota::factory()->create();
        $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

        $this->actingAs($user)
            ->get(route('panel.laporan.daftar-hadir-mingguan'))
            ->assertForbidden();
    }

    public function test_minggu_di_luar_rentang_tidak_menyebabkan_error(): void
    {
        $admin = User::factory()->create(['role' => PeranPengguna::Admin]);

        $this->actingAs($admin)
            ->get(route('panel.laporan.daftar-hadir-mingguan', ['minggu' => 99]))
            ->assertRedirect(route('panel.laporan.daftar-hadir-mingguan', ['minggu' => 1]))
            ->assertSessionHas('warning');
    }
}
