<?php

namespace Tests\Unit;

use App\Layanan\LayananTokenAbsensi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayananTokenAbsensiTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_persistent_token(): void
    {
        $first = LayananTokenAbsensi::getActive();
        $second = LayananTokenAbsensi::getActive();

        $this->assertNotEmpty($first->token);
        $this->assertSame($first->id, $second->id);
        $this->assertSame($first->token, $second->token);
    }

    public function test_rejects_invalid_token(): void
    {
        LayananTokenAbsensi::getActive();

        $this->assertFalse(LayananTokenAbsensi::isValid('token-salah'));
    }

    public function test_regenerate_invalidates_old_token(): void
    {
        $old = LayananTokenAbsensi::getActive()->token;
        LayananTokenAbsensi::regenerate();

        $this->assertFalse(LayananTokenAbsensi::isValid($old));
        $this->assertTrue(LayananTokenAbsensi::isValid(LayananTokenAbsensi::getActive()->token));
    }

    public function test_check_in_url_contains_token(): void
    {
        $token = LayananTokenAbsensi::getActive();
        $url = LayananTokenAbsensi::checkInUrl($token);

        $this->assertStringContainsString($token->token, $url);
    }
}
