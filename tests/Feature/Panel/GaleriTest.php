<?php

namespace Tests\Feature\Panel;

use App\Models\Galeri;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GaleriTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_galeri_index(): void
    {
        $this->get(route('panel.galeri.index'))->assertRedirect(route('login'));
    }

    public function test_galeri_can_store_multiple_photos(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('panel.galeri.store'), [
                'foto' => [
                    UploadedFile::fake()->image('satu.jpg'),
                    UploadedFile::fake()->image('dua.jpg'),
                ],
                'keterangan' => ['Foto 1', 'Foto 2'],
            ])
            ->assertRedirect(route('panel.galeri.index'));

        $this->assertDatabaseCount('galeri', 2);
        $this->assertSame('Foto 1', Galeri::first()->keterangan);
    }

    public function test_galeri_destroy_deletes_record(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $path = UploadedFile::fake()->image('hapus.jpg')->store('galeri', 'public');
        $galeri = Galeri::create(['foto' => $path, 'keterangan' => 'Test']);

        $this->actingAs($user)
            ->delete(route('panel.galeri.destroy', $galeri))
            ->assertRedirect(route('panel.galeri.index'));

        $this->assertDatabaseMissing('galeri', ['id' => $galeri->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
