<?php

declare(strict_types=1);

namespace App\Support;

use App\Support\SectionTypes\AboutSectionType;
use App\Support\SectionTypes\BaseSectionType;
use App\Support\SectionTypes\ContactSectionType;
use App\Support\SectionTypes\CtaBannerSectionType;
use App\Support\SectionTypes\DonateSectionType;
use App\Support\SectionTypes\FaqSectionType;
use App\Support\SectionTypes\GallerySectionType;
use App\Support\SectionTypes\HeroSectionType;
use App\Support\SectionTypes\HowItWorksSectionType;
use App\Support\SectionTypes\ImpactStatsSectionType;
use App\Support\SectionTypes\MemberGroupsSectionType;
use App\Support\SectionTypes\NewsSectionType;
use App\Support\SectionTypes\PartnersSectionType;
use App\Support\SectionTypes\ProgramsSectionType;
use App\Support\SectionTypes\TeamSectionType;
use App\Support\SectionTypes\TestimonialsSectionType;
use App\Support\SectionTypes\VolunteerSectionType;

class SectionTypes
{
    /**
     * Map of all registered section type keys to their corresponding type handler class.
     *
     * @var array<string, class-string<BaseSectionType>>
     */
    protected static array $types = [
        'hero' => HeroSectionType::class,
        'about' => AboutSectionType::class,
        'member_groups' => MemberGroupsSectionType::class,
        'programs' => ProgramsSectionType::class,
        'impact_stats' => ImpactStatsSectionType::class,
        'how_it_works' => HowItWorksSectionType::class,
        'testimonials' => TestimonialsSectionType::class,
        'gallery' => GallerySectionType::class,
        'donate' => DonateSectionType::class,
        'volunteer' => VolunteerSectionType::class,
        'team' => TeamSectionType::class,
        'partners' => PartnersSectionType::class,
        'news' => NewsSectionType::class,
        'faq' => FaqSectionType::class,
        'contact' => ContactSectionType::class,
        'cta_banner' => CtaBannerSectionType::class,
    ];

    /**
     * Retrieve all registered section type classes.
     *
     * @return array<string, class-string<BaseSectionType>>
     */
    public static function all(): array
    {
        return static::$types;
    }

    /**
     * Find a registered section type by key.
     *
     * @return class-string<BaseSectionType>|null
     */
    public static function find(string $key): ?string
    {
        return static::$types[$key] ?? null;
    }

    /**
     * Check if a section type key is registered.
     */
    public static function has(string $key): bool
    {
        return isset(static::$types[$key]);
    }

    /**
     * Get associative array of [key => label] for select inputs.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (static::$types as $key => $class) {
            $options[$key] = $class::getLabel();
        }

        return $options;
    }

    /**
     * Retrieve the human-readable label for a given section type.
     */
    public static function label(string $key): string
    {
        $class = static::find($key);

        return $class !== null ? $class::getLabel() : ucfirst($key);
    }

    /**
     * Retrieve the default content array for a given section type.
     *
     * @return array<string, mixed>
     */
    public static function defaultContent(string $key): array
    {
        $class = static::find($key);

        return $class !== null ? $class::getDefaultContent() : [];
    }

    /**
     * Retrieve the icon for a given section type.
     */
    public static function icon(string $key): string
    {
        $class = static::find($key);

        return $class !== null ? $class::getIcon() : 'heroicon-o-rectangle-stack';
    }
}
