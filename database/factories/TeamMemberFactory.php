<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamMember>
 */
class TeamMemberFactory extends Factory
{
    protected $model = TeamMember::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => fake()->jobTitle(),
            'photo' => 'team/'.fake()->uuid().'.webp',
            'bio' => fake()->paragraph(),
            'member_group_id' => null,
            'is_visible' => true,
            'sort_order' => fake()->numberBetween(0, 50),
        ];
    }
}
