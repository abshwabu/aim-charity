<?php

declare(strict_types=1);

namespace App\Support;

class ColorHelper
{
    /**
     * Calculate WCAG 2.1 relative luminance for a hex color.
     */
    public static function luminance(string $hex): float
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        if (strlen($hex) !== 6 || ! ctype_xdigit($hex)) {
            return 0.5; // fallback
        }

        $r = hexdec(substr($hex, 0, 2)) / 255.0;
        $g = hexdec(substr($hex, 2, 2)) / 255.0;
        $b = hexdec(substr($hex, 4, 2)) / 255.0;

        $rLinear = ($r <= 0.03928) ? ($r / 12.92) : ((($r + 0.055) / 1.055) ** 2.4);
        $gLinear = ($g <= 0.03928) ? ($g / 12.92) : ((($g + 0.055) / 1.055) ** 2.4);
        $bLinear = ($b <= 0.03928) ? ($b / 12.92) : ((($b + 0.055) / 1.055) ** 2.4);

        return (0.2126 * $rLinear) + (0.7152 * $gLinear) + (0.0722 * $bLinear);
    }

    /**
     * Determine if a hex color is perceived as dark.
     */
    public static function isDark(string $hex): bool
    {
        return static::luminance($hex) < 0.45;
    }

    /**
     * Automatically choose high-contrast text color (white or dark charcoal) based on background luminance.
     */
    public static function contrastTextColor(string $bgColor, string $lightText = '#ffffff', string $darkText = '#1c1917'): string
    {
        return static::isDark($bgColor) ? $lightText : $darkText;
    }

    /**
     * Adjust brightness of a hex color for hover/active states.
     *
     * @param  int  $percent  Negative for darker, positive for lighter (-100 to 100)
     */
    public static function adjustBrightness(string $hex, int $percent): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        if (strlen($hex) !== 6 || ! ctype_xdigit($hex)) {
            return '#'.$hex;
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $factor = (100 + $percent) / 100.0;

        $r = (int) min(255, max(0, round($r * $factor)));
        $g = (int) min(255, max(0, round($g * $factor)));
        $b = (int) min(255, max(0, round($b * $factor)));

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    /**
     * Map a radius style string to a valid CSS measurement string.
     */
    public static function radiusValue(?string $style): string
    {
        return match ($style) {
            'sharp', 'rounded-none' => '0px',
            'pill', 'rounded-full' => '9999px',
            'rounded-sm' => '0.25rem',
            'rounded-md' => '0.375rem',
            'rounded-lg' => '0.5rem',
            'rounded-2xl' => '1rem',
            'rounded-3xl' => '1.5rem',
            default => '0.75rem', // rounded-xl
        };
    }

    /**
     * Generate Google Fonts embed URL for heading and body fonts.
     */
    public static function googleFontsUrl(?string $headingFont, ?string $bodyFont): ?string
    {
        $heading = trim($headingFont ?? 'Fraunces');
        $body = trim($bodyFont ?? 'Plus Jakarta Sans');

        $systemFonts = ['system-ui', 'sans-serif', 'serif', 'monospace', 'custom', 'inherit'];

        $fonts = [];

        if (! in_array(strtolower($heading), $systemFonts, true) && filled($heading)) {
            $fonts[] = $heading;
        }

        if (! in_array(strtolower($body), $systemFonts, true) && filled($body) && $body !== $heading) {
            $fonts[] = $body;
        }

        if (empty($fonts)) {
            return null;
        }

        $families = array_map(function (string $font): string {
            $encoded = str_replace(' ', '+', $font);

            // If Fraunces, request optical size and weights
            if (strtolower($font) === 'fraunces') {
                return "family={$encoded}:ital,opsz,wght@0,9..144,400..800;1,9..144,400..800";
            }

            return "family={$encoded}:ital,wght@0,400..800;1,400..800";
        }, $fonts);

        return 'https://fonts.googleapis.com/css2?'.implode('&', $families).'&display=swap';
    }
}
