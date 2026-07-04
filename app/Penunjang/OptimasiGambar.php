<?php

namespace App\Penunjang;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Resize & simpan upload gambar ke storage public (GD).
 */
class OptimasiGambar
{
    public static function simpanDenganResize(UploadedFile $file, string $path, int $maxWidth = 1200): string
    {
        if (! extension_loaded('gd')) {
            return $file->store(trim($path, '/'), 'public');
        }

        $extension = self::normalisasiEkstensi($file->getClientOriginalExtension());
        $filename = Str::random(40).'.'.$extension;
        $relativePath = trim($path, '/').'/'.$filename;

        $info = @getimagesize($file->getRealPath());
        if ($info === false) {
            throw new RuntimeException('Gambar tidak valid.');
        }

        [$width, $height] = $info;

        if ($width <= $maxWidth) {
            Storage::disk('public')->putFileAs(trim($path, '/'), $file, $filename);

            return $relativePath;
        }

        $src = self::bacaGambar($file->getRealPath(), $extension);
        if ($src === false) {
            Storage::disk('public')->putFileAs(trim($path, '/'), $file, $filename);

            return $relativePath;
        }

        $newWidth = $maxWidth;
        $newHeight = (int) round($height * ($maxWidth / $width));

        $dst = imagecreatetruecolor($newWidth, $newHeight);
        self::pertahankanTransparansi($dst, $extension);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $fullPath = Storage::disk('public')->path($relativePath);
        $dir = dirname($fullPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        self::simpanGambar($dst, $fullPath, $extension);

        imagedestroy($src);
        imagedestroy($dst);

        return $relativePath;
    }

    private static function normalisasiEkstensi(string $extension): string
    {
        $extension = strtolower($extension);

        return $extension === 'jpeg' ? 'jpg' : $extension;
    }

    /** @return \GdImage|false */
    private static function bacaGambar(string $path, string $extension): \GdImage|false
    {
        return match ($extension) {
            'jpg' => @imagecreatefromjpeg($path),
            'png' => @imagecreatefrompng($path),
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };
    }

    /** @param \GdImage $image */
    private static function pertahankanTransparansi($image, string $extension): void
    {
        if ($extension !== 'png' && $extension !== 'webp') {
            return;
        }

        imagealphablending($image, false);
        imagesavealpha($image, true);
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefilledrectangle($image, 0, 0, imagesx($image), imagesy($image), $transparent);
    }

    /** @param \GdImage $image */
    private static function simpanGambar($image, string $path, string $extension): void
    {
        match ($extension) {
            'jpg' => imagejpeg($image, $path, 85),
            'png' => imagepng($image, $path, 6),
            'webp' => imagewebp($image, $path, 85),
            default => throw new RuntimeException("Format gambar tidak didukung: {$extension}"),
        };
    }
}
