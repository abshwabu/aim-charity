<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasSortOrderAndVisibility;
use Database\Factories\PageSectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    /** @use HasFactory<PageSectionFactory> */
    use FlushesSiteCache, HasFactory, HasSortOrderAndVisibility;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'type',
        'sort_order',
        'is_visible',
        'nav_label',
        'anchor',
        'content',
        'style',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_visible' => 'boolean',
            'content' => 'array',
            'style' => 'array',
        ];
    }
}
