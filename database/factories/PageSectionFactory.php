<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PageSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageSection>
 */
class PageSectionFactory extends Factory
{
    protected $model = PageSection::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [
            'hero', 'about', 'member_groups', 'programs', 'impact_stats',
            'how_it_works', 'testimonials', 'gallery', 'news', 'donate',
            'volunteer', 'partners', 'faq', 'contact', 'cta_banner',
        ];

        return [
            'key' => fake()->unique()->slug(2),
            'type' => fake()->randomElement($types),
            'sort_order' => fake()->numberBetween(0, 100),
            'is_visible' => true,
            'nav_label' => fake()->word(),
            'anchor' => fake()->slug(1),
            'content' => [
                'eyebrow' => fake()->words(2, true),
                'heading' => fake()->sentence(3),
                'subheading' => fake()->sentence(6),
                'body' => '<p>'.fake()->paragraph().'</p>',
                'images' => ['sections/sample.webp'],
                'buttons' => [
                    ['label' => 'Learn More', 'url' => '#', 'style' => 'primary'],
                ],
            ],
            'style' => [
                'background_color' => '#ffffff',
                'background_image' => null,
                'background_overlay' => null,
                'text_theme' => 'light',
                'padding_size' => 'default',
            ],
        ];
    }
}
