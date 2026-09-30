<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\VolunteerApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerApplication extends Model
{
    /** @use HasFactory<VolunteerApplicationFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'member_group_id',
        'skills',
        'availability',
        'message',
        'status',
    ];

    /**
     * Get the member group chosen in this application, if any.
     *
     * @return BelongsTo<MemberGroup, $this>
     */
    public function memberGroup(): BelongsTo
    {
        return $this->belongsTo(MemberGroup::class);
    }
}
