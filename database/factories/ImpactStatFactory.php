<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ImpactStat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ImpactStat>
 */
class ImpactStatFactory extends Factory
{
    protected $model = ImpactStat::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'value' => (string) fake()->numberBetween(10, 500),
            'suffix' => '+',
            'label' => fake()->words(3, true),
            'icon' => 'heroicon-o-user-group',
            'is_visible' => true,
            'sort_order' => fake()->numberBetween(0, 50),
        ];
    }
}
