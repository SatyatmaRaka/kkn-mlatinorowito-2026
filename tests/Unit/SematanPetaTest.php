<?php

namespace Tests\Unit;

use App\Penunjang\SematanPeta;
use Tests\TestCase;

class SematanPetaTest extends TestCase
{
    public function test_accepts_google_maps_embed_url(): void
    {
        $url = 'https://maps.google.com/maps?q=Test&t=&z=15&output=embed';

        $this->assertTrue(SematanPeta::isValidEmbedUrl($url));
        $this->assertSame($url, SematanPeta::safeUrl($url));
    }

    public function test_rejects_malicious_embed_url(): void
    {
        $url = 'https://evil.example/phishing';

        $this->assertFalse(SematanPeta::isValidEmbedUrl($url));
        $this->assertSame(SematanPeta::defaultUrl(), SematanPeta::safeUrl($url));
    }

    public function test_empty_input_is_valid_for_form(): void
    {
        $this->assertTrue(SematanPeta::isValidEmbedInput(null));
        $this->assertTrue(SematanPeta::isValidEmbedInput(''));
    }
}
