<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\VolunteerApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VolunteerApplication>
 */
class VolunteerApplicationFactory extends Factory
{
    protected $model = VolunteerApplication::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'member_group_id' => null,
            'skills' => fake()->words(4, true),
            'availability' => 'Weekends',
            'message' => fake()->paragraph(),
            'status' => 'pending',
        ];
    }
}
