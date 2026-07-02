<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Logbook;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Logbook>
 */
class LogbookFactory extends Factory
{
    protected $model = Logbook::class;

    public function definition(): array
    {
        return [
            'tanggal' => fake()->date(),
            'judul' => fake()->sentence(4),
            'deskripsi' => fake()->paragraph(),
            'status' => Logbook::STATUS_DRAFT,
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Logbook $logbook) {
            if ($logbook->user_id && $logbook->anggota_id) {
                return;
            }

            $anggota = Anggota::factory()->create();
            $user = User::factory()->anggota()->create(['anggota_id' => $anggota->id]);

            $logbook->user_id ??= $user->id;
            $logbook->anggota_id ??= $anggota->id;
        });
    }

    public function submitted(): static
    {
        return $this->state(fn () => ['status' => Logbook::STATUS_SUBMITTED]);
    }

    public function approved(): static
    {
        return $this->state(fn () => ['status' => Logbook::STATUS_APPROVED]);
    }
}
