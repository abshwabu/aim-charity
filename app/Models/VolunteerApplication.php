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

    public const STATUS_NEW = 'new';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_DECLINED = 'declined';

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
        'notes',
    ];

    /**
     * Get associative array of all application statuses.
     *
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_CONTACTED => 'Contacted',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_DECLINED => 'Declined',
        ];
    }

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
