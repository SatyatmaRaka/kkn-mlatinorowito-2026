<?php

namespace Database\Factories;

use App\Enums\UserRole;
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
            'role' => UserRole::Admin,
            'remember_token' => Str::random(10),
        ];
    }

    public function anggota(): static
    {
        return $this->state(fn () => ['role' => UserRole::Anggota]);
    }

    public function koordinator(): static
    {
        return $this->state(fn () => ['role' => UserRole::Koordinator]);
    }
}
