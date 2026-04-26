<?php

namespace App\Models;

use App\Enums\UserFriendshipStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFriendship extends Model
{
    use HasFactory;

    protected $table = 'user_friendships';

    protected $fillable = [
        'request_from',
        'request_to',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => UserFriendshipStatusEnum::class,
        ];
    }

    public function from(): BelongsTo
    {
        return $this->belongsTo(User::class, 'request_from');
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(User::class, 'request_to');
    }
}
