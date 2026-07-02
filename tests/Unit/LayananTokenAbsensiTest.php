<?php

namespace Tests\Unit;

use App\Layanan\LayananTokenAbsensi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayananTokenAbsensiTest extends TestCase
{
    use RefreshDatabase;
    public function test_creates_token_for_today(): void
    {
        $token = LayananTokenAbsensi::getOrCreateForToday();

        $this->assertNotEmpty($token->token);
        $this->assertSame(today()->toDateString(), $token->tanggal->toDateString());
    }

    public function test_rejects_invalid_token(): void
    {
        $this->assertFalse(LayananTokenAbsensi::isValid('token-salah'));
    }

    public function test_check_in_url_contains_token(): void
    {
        $token = LayananTokenAbsensi::getOrCreateForToday();
        $url = LayananTokenAbsensi::checkInUrl($token);

        $this->assertStringContainsString($token->token, $url);
    }
}
