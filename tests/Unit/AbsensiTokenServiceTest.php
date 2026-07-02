<?php

namespace Tests\Unit;

use App\Services\AbsensiTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsensiTokenServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_token_for_today(): void
    {
        $token = AbsensiTokenService::getOrCreateForToday();

        $this->assertNotEmpty($token->token);
        $this->assertTrue(AbsensiTokenService::isValid($token->token));
    }

    public function test_rejects_invalid_token(): void
    {
        AbsensiTokenService::getOrCreateForToday();

        $this->assertFalse(AbsensiTokenService::isValid('token-salah'));
        $this->assertFalse(AbsensiTokenService::isValid(null));
    }

    public function test_check_in_url_contains_token(): void
    {
        $url = AbsensiTokenService::checkInUrl();

        $this->assertStringContainsString('token=', $url);
    }
}
