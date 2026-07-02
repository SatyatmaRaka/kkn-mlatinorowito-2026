<?php

namespace Database\Factories;

use App\Enums\Jabatan;
use App\Models\Anggota;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Anggota>
 */
class AnggotaFactory extends Factory
{
    protected $model = Anggota::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'nim' => fake()->numerify('##########'),
            'jurusan' => 'Sistem Informasi',
            'jabatan' => Jabatan::Humas->value,
            'urutan' => 1,
        ];
    }

    public function sekretaris(): static
    {
        return $this->state(fn () => ['jabatan' => Jabatan::Sekretaris->value]);
    }

    public function bendahara(): static
    {
        return $this->state(fn () => ['jabatan' => Jabatan::Bendahara->value]);
    }
}
