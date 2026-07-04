<?php

namespace Tests\Unit;

use App\Models\Logbook;
use App\Models\User;
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
        $user = User::factory()->create();
        Logbook::factory()->create(['user_id' => $user->id, 'judul' => 'Undangan rapat koordinasi']);
        Logbook::factory()->create(['user_id' => $user->id, 'judul' => 'Lainnya']);

        $hasil = Logbook::query()
            ->when(true, fn ($q) => FilterPencarian::terapkan($q, 'rapat', ['judul']))
            ->get();

        $this->assertCount(1, $hasil);
        $this->assertSame('Undangan rapat koordinasi', $hasil->first()->judul);
    }
}
