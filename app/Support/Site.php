<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\PageSection;
use App\Models\SiteSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Throwable;

class Site
{
    public const CACHE_SETTINGS_KEY = 'site:settings';

    public const CACHE_SECTIONS_KEY = 'site:sections';

    /**
     * Retrieve the singleton site settings, cached indefinitely.
     */
    public static function settings(): SiteSetting
    {
        return Cache::rememberForever(self::CACHE_SETTINGS_KEY, function (): SiteSetting {
            return SiteSetting::current();
        });
    }

    /**
     * Retrieve all visible page sections ordered by sort_order, keyed by key.
     *
     * @return Collection<string, PageSection>
     */
    public static function sections(): Collection
    {
        return Cache::rememberForever(self::CACHE_SECTIONS_KEY, function (): Collection {
            try {
                return PageSection::query()
                    ->visible()
                    ->ordered()
                    ->get()
                    ->keyBy('key');
            } catch (Throwable) {
                return collect();
            }
        });
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
     * Flush all cached site settings and sections.
     */
    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_SETTINGS_KEY);
        Cache::forget(self::CACHE_SECTIONS_KEY);
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
