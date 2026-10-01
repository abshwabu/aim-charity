<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\CleansUpMediaOnDeleteAndReplace;
use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasSortOrderAndVisibility;
use Database\Factories\ProgramFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    /** @use HasFactory<ProgramFactory> */
    use CleansUpMediaOnDeleteAndReplace, FlushesSiteCache, HasFactory, HasSortOrderAndVisibility;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'icon',
        'description',
        'image',
        'link_url',
        'link_label',
        'is_visible',
        'sort_order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
