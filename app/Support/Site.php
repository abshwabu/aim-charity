<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\DonationMethod;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\ImpactStat;
use App\Models\MemberGroup;
use App\Models\NewsPost;
use App\Models\PageSection;
use App\Models\Partner;
use App\Models\Program;
use App\Models\SiteSetting;
use App\Models\Step;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Throwable;

class Site
{
    public const CACHE_SETTINGS_KEY = 'site:settings';

    public const CACHE_SECTIONS_KEY = 'site:sections';

    public const CACHE_ITEMS_KEY = 'site:items';

    /**
     * Retrieve the singleton site settings, cached indefinitely.
     */
    /**
     * Retrieve the singleton site settings, cached indefinitely.
     */
    public static function settings(): SiteSetting
    {
        class_exists(SiteSetting::class);

        try {
            $cached = Cache::get(self::CACHE_SETTINGS_KEY);

            if ($cached instanceof SiteSetting && ! ($cached instanceof \__PHP_Incomplete_Class)) {
                return $cached;
            }
        } catch (Throwable) {
            Cache::forget(self::CACHE_SETTINGS_KEY);
        }

        $settings = SiteSetting::current();

        try {
            Cache::forever(self::CACHE_SETTINGS_KEY, $settings);
        } catch (Throwable) {
            // Ignore cache storage failure
        }

        return $settings;
    }

    /**
     * Retrieve all visible page sections ordered by sort_order, keyed by key.
     *
     * @return Collection<string, PageSection>
     */
    public static function sections(): Collection
    {
        class_exists(PageSection::class);
        class_exists(Collection::class);

        try {
            $cached = Cache::get(self::CACHE_SECTIONS_KEY);

            if ($cached instanceof Collection && ! ($cached instanceof \__PHP_Incomplete_Class)) {
                return $cached;
            }
        } catch (Throwable) {
            Cache::forget(self::CACHE_SECTIONS_KEY);
        }

        try {
            $sections = PageSection::query()
                ->visible()
                ->ordered()
                ->get()
                ->keyBy('key');
        } catch (Throwable) {
            $sections = collect();
        }

        try {
            Cache::forever(self::CACHE_SECTIONS_KEY, $sections);
        } catch (Throwable) {
            // Ignore cache storage failure
        }

        return $sections;
    }

    /**
     * Retrieve a specific page section by its unique key with null-safe defaults.
     */
    public static function section(string $key): PageSection
    {
        $section = static::sections()->get($key);

        if ($section instanceof PageSection) {
            return $section;
        }

        return new PageSection([
            'key' => $key,
            'type' => $key,
            'sort_order' => 0,
            'is_visible' => false,
            'nav_label' => null,
            'anchor' => null,
            'content' => [
                'eyebrow' => null,
                'heading' => null,
                'subheading' => null,
                'body' => null,
                'images' => [],
                'buttons' => [],
            ],
            'style' => [
                'background_color' => null,
                'background_image' => null,
                'background_overlay' => null,
                'text_theme' => 'light',
                'padding_size' => 'default',
            ],
        ]);
    }

    /**
     * Retrieve all visible repeatable items bundled by section key, cached.
     *
     * @return array<string, Collection>
     */
    public static function items(): array
    {
        class_exists(Collection::class);
        class_exists(MemberGroup::class);
        class_exists(Program::class);
        class_exists(ImpactStat::class);
        class_exists(Step::class);
        class_exists(Testimonial::class);
        class_exists(GalleryItem::class);
        class_exists(NewsPost::class);
        class_exists(DonationMethod::class);
        class_exists(Partner::class);
        class_exists(Faq::class);
        class_exists(TeamMember::class);

        try {
            $cached = Cache::get(self::CACHE_ITEMS_KEY);

            if (is_array($cached)) {
                $hasIncomplete = false;
                foreach ($cached as $entry) {
                    if ($entry instanceof \__PHP_Incomplete_Class || ! ($entry instanceof Collection)) {
                        $hasIncomplete = true;
                        break;
                    }
                }

                if (! $hasIncomplete) {
                    return $cached;
                }
            }
        } catch (Throwable) {
            Cache::forget(self::CACHE_ITEMS_KEY);
        }

        try {
            $items = [
                'member_groups' => MemberGroup::query()->visible()->ordered()->get(),
                'programs' => Program::query()->visible()->ordered()->get(),
                'impact_stats' => ImpactStat::query()->visible()->ordered()->get(),
                'how_it_works' => Step::query()->visible()->ordered()->get(),
                'testimonials' => Testimonial::query()->with('memberGroup')->visible()->ordered()->get(),
                'gallery' => GalleryItem::query()->with('memberGroup')->visible()->ordered()->get(),
                'news' => NewsPost::query()->visible()->ordered()->latest('published_at')->get(),
                'donate' => DonationMethod::query()->visible()->ordered()->get(),
                'partners' => Partner::query()->visible()->ordered()->get(),
                'faq' => Faq::query()->visible()->ordered()->get(),
                'team' => TeamMember::query()->with('memberGroup')->visible()->ordered()->get(),
            ];
        } catch (Throwable) {
            $items = [];
        }

        try {
            Cache::forever(self::CACHE_ITEMS_KEY, $items);
        } catch (Throwable) {
            // Ignore cache storage failure
        }

        return $items;
    }

    /**
     * Flush all cached site settings, sections, and items.
     */
    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_SETTINGS_KEY);
        Cache::forget(self::CACHE_SECTIONS_KEY);
        Cache::forget(self::CACHE_ITEMS_KEY);
    }

    /**
     * Resolve a relative public storage path to a fully-qualified URL with null safety.
     */
    public static function imageUrl(?string $path, ?string $default = null): ?string
    {
        if (blank($path)) {
            return $default;
        }

        if (
            str_starts_with($path, 'http://') ||
            str_starts_with($path, 'https://') ||
            str_starts_with($path, '//') ||
            str_starts_with($path, 'data:')
        ) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
