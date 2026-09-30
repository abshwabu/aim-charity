<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    protected $model = Program::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'icon' => 'heroicon-o-academic-cap',
            'description' => fake()->paragraph(),
            'image' => 'programs/'.fake()->uuid().'.webp',
            'link_url' => fake()->url(),
            'link_label' => 'Learn More',
            'is_visible' => true,
            'sort_order' => fake()->numberBetween(0, 50),
        ];
    }
}
