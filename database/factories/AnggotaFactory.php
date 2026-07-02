<?php

namespace Database\Factories;

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
            'jabatan' => 'Humas',
            'urutan' => 1,
        ];
    }
}
