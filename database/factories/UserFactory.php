<?php

namespace Database\Factories;

use App\Enums\Jabatan;
use App\Enums\PeranPengguna;
use App\Models\Anggota;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => PeranPengguna::Admin,
            'remember_token' => Str::random(10),
        ];
    }

    public function anggota(): static
    {
        return $this->state(fn () => ['role' => PeranPengguna::Anggota]);
    }

    public function koordinator(): static
    {
        return $this->state(function () {
            $anggota = Anggota::factory()->create(['jabatan' => Jabatan::KoordinatorDesa->value]);

            return [
                'role' => PeranPengguna::Koordinator,
                'anggota_id' => $anggota->id,
            ];
        });
    }

    public function sekretaris(): static
    {
        return $this->state(function () {
            $anggota = Anggota::factory()->create(['jabatan' => Jabatan::Sekretaris->value]);

            return [
                'role' => PeranPengguna::Anggota,
                'anggota_id' => $anggota->id,
            ];
        });
    }

    public function bendahara(): static
    {
        return $this->state(function () {
            $anggota = Anggota::factory()->create(['jabatan' => Jabatan::Bendahara->value]);

            return [
                'role' => PeranPengguna::Anggota,
                'anggota_id' => $anggota->id,
            ];
        });
    }
}
