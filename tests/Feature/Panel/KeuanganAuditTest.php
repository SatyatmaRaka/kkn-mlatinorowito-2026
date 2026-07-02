<?php

namespace Tests\Feature\Panel;

use App\Models\Keuangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeuanganAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_records_diubah_oleh(): void
    {
        $admin = User::factory()->create();
        $editor = User::factory()->create();

        $keuangan = Keuangan::create([
            'user_id' => $admin->id,
            'tanggal' => '2026-07-01',
            'jenis' => 'pemasukan',
            'keterangan' => 'Dana awal',
            'nominal' => 100000,
        ]);

        $this->actingAs($editor)
            ->put(route('panel.keuangan.update', $keuangan), [
                'tanggal' => '2026-07-01',
                'jenis' => 'pemasukan',
                'keterangan' => 'Dana awal (revisi)',
                'nominal' => 150000,
            ])
            ->assertRedirect(route('panel.keuangan.index'));

        $keuangan->refresh();

        $this->assertSame($editor->id, $keuangan->diubah_oleh);
        $this->assertSame('Dana awal (revisi)', $keuangan->keterangan);
    }
}
