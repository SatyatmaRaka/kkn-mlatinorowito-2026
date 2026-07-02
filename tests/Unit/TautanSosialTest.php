<?php

namespace Tests\Unit;

use App\Penunjang\TautanSosial;
use Tests\TestCase;

class TautanSosialTest extends TestCase
{
    public function test_instagram_url_uses_default_when_empty(): void
    {
        $this->assertSame(
            'https://www.instagram.com/kknumk.mlatinorowito.26',
            TautanSosial::instagramUrl(null)
        );
    }

    public function test_instagram_url_builds_from_handle(): void
    {
        $this->assertSame(
            'https://www.instagram.com/test.handle',
            TautanSosial::instagramUrl('@test.handle')
        );
    }

    public function test_instagram_url_accepts_valid_instagram_link(): void
    {
        $url = 'https://www.instagram.com/validuser';

        $this->assertSame($url, TautanSosial::instagramUrl($url));
    }

    public function test_instagram_url_rejects_malicious_external_link(): void
    {
        $this->assertSame(
            'https://www.instagram.com/kknumk.mlatinorowito.26',
            TautanSosial::instagramUrl('https://evil.example/phishing')
        );
    }

    /** @dataProvider validInstagramInputProvider */
    public function test_valid_instagram_input(mixed $input): void
    {
        $this->assertTrue(TautanSosial::isValidInstagramInput($input));
    }

    /** @return array<string, array{0: mixed}> */
    public static function validInstagramInputProvider(): array
    {
        return [
            'empty' => [null],
            'handle' => ['@valid_user'],
            'handle without at' => ['valid.user'],
            'instagram url' => ['https://www.instagram.com/valid'],
        ];
    }

    /** @dataProvider invalidInstagramInputProvider */
    public function test_invalid_instagram_input(mixed $input): void
    {
        $this->assertFalse(TautanSosial::isValidInstagramInput($input));
    }

    /** @return array<string, array{0: mixed}> */
    public static function invalidInstagramInputProvider(): array
    {
        return [
            'external url' => ['https://evil.example/phishing'],
            'javascript scheme' => ['javascript:alert(1)'],
            'spaces in handle' => ['invalid handle'],
        ];
    }
}
