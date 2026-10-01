<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\CleansUpMediaOnDeleteAndReplace;
use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasSortOrderAndVisibility;
use Database\Factories\MemberGroupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberGroup extends Model
{
    /** @use HasFactory<MemberGroupFactory> */
    use CleansUpMediaOnDeleteAndReplace, FlushesSiteCache, HasFactory, HasSortOrderAndVisibility;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'logo',
        'short_description',
        'long_description',
        'focus_area',
        'founded_year',
        'website_url',
        'social_links',
        'photo',
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
            'social_links' => 'array',
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get all testimonials associated with this member group.
     *
     * @return HasMany<Testimonial, $this>
     */
    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    /**
     * Get all gallery items associated with this member group.
     *
     * @return HasMany<GalleryItem, $this>
     */
    public function galleryItems(): HasMany
    {
        return $this->hasMany(GalleryItem::class);
    }

    /**
     * Get all team members associated with this member group.
     *
     * @return HasMany<TeamMember, $this>
     */
    public function teamMembers(): HasMany
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * Get all volunteer applications associated with this member group.
     *
     * @return HasMany<VolunteerApplication, $this>
     */
    public function volunteerApplications(): HasMany
    {
        return $this->hasMany(VolunteerApplication::class);
    }
}
