<?php

namespace Tests\Unit;

use App\Models\Surat;
use App\Penunjang\FilterPencarian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterPencarianTest extends TestCase
{
    use RefreshDatabase;

    public function test_kata_kunci_kosong_dikembalikan_null(): void
    {
        $this->assertNull(FilterPencarian::kataKunci(null));
        $this->assertNull(FilterPencarian::kataKunci('   '));
    }

    public function test_terapkan_mencari_di_kolom(): void
    {
        Surat::factory()->masuk()->create(['perihal' => 'Undangan rapat koordinasi']);
        Surat::factory()->masuk()->create(['perihal' => 'Lainnya']);

        $hasil = Surat::query()
            ->when(true, fn ($q) => FilterPencarian::terapkan($q, 'rapat', ['perihal']))
            ->get();

        $this->assertCount(1, $hasil);
        $this->assertSame('Undangan rapat koordinasi', $hasil->first()->perihal);
    }
}
