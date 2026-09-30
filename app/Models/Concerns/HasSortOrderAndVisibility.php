<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait HasSortOrderAndVisibility
{
    /**
     * Scope a query to only include visible items.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    #[Scope]
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope a query to order items by their sort order.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    #[Scope]
    public function scopeOrdered(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('sort_order', $direction);
    }
}
