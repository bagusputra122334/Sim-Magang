<?php

namespace Database\Factories;

use App\Enums\PositionStatus;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Position>
 */
class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition(): array
    {
        $name = fake()->jobTitle();

        return [
            'nama_posisi' => $name,
            'slug'        => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 999),
            'deskripsi'   => fake()->paragraph(),
            'kualifikasi' => fake()->sentence(),
            'kuota'       => 5,
            'status'      => PositionStatus::Aktif,
            'opd_id'      => null,
        ];
    }
}
