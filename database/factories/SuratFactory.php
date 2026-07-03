<?php

namespace Database\Factories;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Surat>
 */
class SuratFactory extends Factory
{
    protected $model = Surat::class;

    public function definition(): array
    {
        $jenis = fake()->randomElement(['masuk', 'keluar']);

        return [
            'jenis' => $jenis,
            'nomor_surat' => fake()->optional()->numerify('###/KKN/VII/2026'),
            'tanggal' => fake()->dateTimeBetween('-1 month', 'now'),
            'asal_tujuan' => $jenis === 'masuk'
                ? fake()->company()
                : 'Kelurahan Mlatinorowito',
            'perihal' => fake()->sentence(4),
            'keterangan' => fake()->optional()->sentence(),
            'lampiran' => null,
            'user_id' => User::factory(),
        ];
    }

    public function masuk(): static
    {
        return $this->state(fn () => ['jenis' => 'masuk']);
    }

    public function keluar(): static
    {
        return $this->state(fn () => ['jenis' => 'keluar']);
    }
}
