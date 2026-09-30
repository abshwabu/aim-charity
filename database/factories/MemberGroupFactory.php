<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MemberGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MemberGroup>
 */
class MemberGroupFactory extends Factory
{
    protected $model = MemberGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'logo' => 'groups/'.fake()->uuid().'.webp',
            'short_description' => fake()->sentence(8),
            'long_description' => fake()->paragraphs(2, true),
            'focus_area' => fake()->randomElement(['Emergency Relief', 'Education Support', 'Healthcare', 'Women Empowerment', 'Youth Development']),
            'founded_year' => (string) fake()->numberBetween(2000, 2024),
            'website_url' => fake()->url(),
            'social_links' => [
                ['platform' => 'facebook', 'url' => 'https://facebook.com/'.fake()->slug()],
            ],
            'photo' => 'groups/photos/'.fake()->uuid().'.webp',
            'is_visible' => true,
            'sort_order' => fake()->numberBetween(0, 50),
        ];
    }
}
