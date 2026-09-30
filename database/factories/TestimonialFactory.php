<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quote' => fake()->paragraph(),
            'author_name' => fake()->name(),
            'author_role' => fake()->jobTitle(),
            'author_photo' => 'testimonials/'.fake()->uuid().'.webp',
            'member_group_id' => null,
            'is_visible' => true,
            'sort_order' => fake()->numberBetween(0, 50),
        ];
    }
}
