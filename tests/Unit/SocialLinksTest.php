<?php

namespace Tests\Unit;

use App\Support\SocialLinks;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SocialLinksTest extends TestCase
{
    public function test_instagram_url_uses_default_when_empty(): void
    {
        $this->assertSame(
            'https://www.instagram.com/kknumk.mlatinorowito.26',
            SocialLinks::instagramUrl(null)
        );
    }

    public function test_instagram_url_builds_from_handle(): void
    {
        $this->assertSame(
            'https://www.instagram.com/test.handle',
            SocialLinks::instagramUrl('@test.handle')
        );
    }

    public function test_instagram_url_accepts_valid_instagram_link(): void
    {
        $url = 'https://www.instagram.com/kknumk.mlatinorowito.26';

        $this->assertSame($url, SocialLinks::instagramUrl($url));
    }

    public function test_instagram_url_rejects_malicious_external_link(): void
    {
        $this->assertSame(
            'https://www.instagram.com/kknumk.mlatinorowito.26',
            SocialLinks::instagramUrl('https://evil.example/phishing')
        );
    }

    #[DataProvider('validInstagramInputProvider')]
    public function test_valid_instagram_input(?string $value): void
    {
        $this->assertTrue(SocialLinks::isValidInstagramInput($value));
    }

    public static function validInstagramInputProvider(): array
    {
        return [
            'empty' => [null],
            'handle' => ['@test_handle'],
            'handle without at' => ['test.handle'],
            'instagram url' => ['https://www.instagram.com/test'],
        ];
    }

    #[DataProvider('invalidInstagramInputProvider')]
    public function test_invalid_instagram_input(string $value): void
    {
        $this->assertFalse(SocialLinks::isValidInstagramInput($value));
    }

    public static function invalidInstagramInputProvider(): array
    {
        return [
            'external url' => ['https://evil.example/phishing'],
            'javascript scheme' => ['javascript:alert(1)'],
            'spaces in handle' => ['bad handle'],
        ];
    }
}
