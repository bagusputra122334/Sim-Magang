<?php

namespace Database\Factories;

use App\Models\Opd;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Opd>
 */
class OpdFactory extends Factory
{
    protected $model = Opd::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban',
            'code' => 'diskominfo-sp',
            'slug' => 'diskominfo-sp',
            'address' => 'Jl. Kartini No. 2 Tuban',
            'phone' => '0356-321012',
            'email' => 'diskominfo@tubankab.go.id',
            'head_of_office_name' => 'Kepala Dinas Kominfo SP Kab. Tuban',
            'head_of_office_nip' => '197001011995011001',
            'logo_path' => null,
            'is_active' => true,
        ];
    }

    /**
     * Generate a unique random OPD.
     */
    public function random(): static
    {
        return $this->state(function (array $attributes) {
            $name = 'Dinas ' . fake()->unique()->words(2, true) . ' Kabupaten Tuban';
            $code = Str::slug($name);

            return [
                'name' => $name,
                'code' => $code,
                'slug' => $code,
                'address' => fake()->address(),
                'phone' => fake()->phoneNumber(),
                'email' => fake()->unique()->safeEmail(),
                'head_of_office_name' => fake()->name(),
                'head_of_office_nip' => fake()->numerify('19##########00#'),
                'logo_path' => null,
                'is_active' => true,
            ];
        });
    }

    /**
     * Indicate that the OPD is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
