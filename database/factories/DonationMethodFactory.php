<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DonationMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DonationMethod>
 */
class DonationMethodFactory extends Factory
{
    protected $model = DonationMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => fake()->randomElement(['Commercial Bank of Ethiopia (CBE)', 'Telebirr', 'Bank of Abyssinia', 'Awash Bank']),
            'logo' => 'donations/'.fake()->uuid().'.webp',
            'account_name' => 'Aim Charity Coalition',
            'account_number' => (string) fake()->numberBetween(1000000000, 9999999999),
            'instructions' => '<p>Please include your full name as reference.</p>',
            'qr_image' => 'donations/qr/'.fake()->uuid().'.webp',
            'is_visible' => true,
            'sort_order' => fake()->numberBetween(0, 50),
        ];
    }
}
