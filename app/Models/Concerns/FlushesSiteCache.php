<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Support\Site;

trait FlushesSiteCache
{
    /**
     * Boot the trait to flush site cache on model lifecycle events.
     */
    public static function bootFlushesSiteCache(): void
    {
        static::saved(function (): void {
            Site::flushCache();
        });

        static::deleted(function (): void {
            Site::flushCache();
        });
    }
}
