<?php

namespace Tests\Unit;

use App\Penunjang\OptimasiGambar;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OptimasiGambarTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_resize_gambar_lebar_melebihi_max_width(): void
    {
        $source = $this->buatGambarSementara(2400, 1600);
        $file = new UploadedFile($source, 'foto.jpg', 'image/jpeg', null, true);

        $path = OptimasiGambar::simpanDenganResize($file, 'anggota', 800);

        Storage::disk('public')->assertExists($path);
        [$width] = getimagesize(Storage::disk('public')->path($path));
        $this->assertSame(800, $width);
    }

    public function test_gambar_kecil_tidak_di_perbesar(): void
    {
        $source = $this->buatGambarSementara(400, 300);
        $file = new UploadedFile($source, 'foto.png', 'image/png', null, true);

        $path = OptimasiGambar::simpanDenganResize($file, 'anggota', 800);

        Storage::disk('public')->assertExists($path);
        [$width, $height] = getimagesize(Storage::disk('public')->path($path));
        $this->assertSame(400, $width);
        $this->assertSame(300, $height);
    }

    public function test_format_webp_pertahankan_ekstensi(): void
    {
        if (! function_exists('imagewebp')) {
            $this->markTestSkipped('WebP tidak didukung GD di environment ini.');
        }

        $source = $this->buatGambarWebpSementara(2000, 1200);
        $file = new UploadedFile($source, 'bukti.webp', 'image/webp', null, true);

        $path = OptimasiGambar::simpanDenganResize($file, 'keuangan', 1200);

        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);
    }

    private function buatGambarSementara(int $width, int $height): string
    {
        $image = imagecreatetruecolor($width, $height);
        $bg = imagecolorallocate($image, 30, 80, 160);
        imagefilledrectangle($image, 0, 0, $width, $height, $bg);

        $path = tempnam(sys_get_temp_dir(), 'optimg_').'.jpg';
        imagejpeg($image, $path, 90);
        imagedestroy($image);

        return $path;
    }

    private function buatGambarWebpSementara(int $width, int $height): string
    {
        $image = imagecreatetruecolor($width, $height);
        $bg = imagecolorallocate($image, 200, 120, 40);
        imagefilledrectangle($image, 0, 0, $width, $height, $bg);

        $path = tempnam(sys_get_temp_dir(), 'optwebp_').'.webp';
        imagewebp($image, $path, 90);
        imagedestroy($image);

        return $path;
    }
}
