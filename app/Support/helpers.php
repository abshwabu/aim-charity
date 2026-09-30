<?php

declare(strict_types=1);

use App\Support\Site;

if (! function_exists('imageUrl')) {
    /**
     * Resolve an image path to a public storage URL with null-safety.
     */
    function imageUrl(?string $path, ?string $default = null): ?string
    {
        return Site::imageUrl($path, $default);
    }
}
