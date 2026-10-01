<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\CleansUpMediaOnDeleteAndReplace;
use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasSortOrderAndVisibility;
use Database\Factories\TestimonialFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    /** @use HasFactory<TestimonialFactory> */
    use CleansUpMediaOnDeleteAndReplace, FlushesSiteCache, HasFactory, HasSortOrderAndVisibility;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'quote',
        'author_name',
        'author_role',
        'author_photo',
        'member_group_id',
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

    /**
     * Get the member group that this testimonial belongs to.
     *
     * @return BelongsTo<MemberGroup, $this>
     */
    public function memberGroup(): BelongsTo
    {
        return $this->belongsTo(MemberGroup::class);
    }
}
