<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $owner_id
 * @property int|null $player_1_id
 * @property string|null $player_1_token
 * @property string|null $player_1_quote
 * @property string|null $player_1_name
 * @property int|null $player_2_id
 * @property string|null $player_2_token
 * @property string|null $player_2_quote
 * @property string|null $player_2_name
 * @property int|null $player_3_id
 * @property string|null $player_3_token
 * @property string|null $player_3_quote
 * @property string|null $player_3_name
 * @property int|null $player_4_id
 * @property string|null $player_4_token
 * @property string|null $player_4_quote
 * @property string|null $player_4_name
 * @property array|null $winners
 * @property array|null $data
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\User|null $player1
 * @property-read \App\Models\User|null $player2
 * @property-read \App\Models\User|null $player3
 * @property-read \App\Models\User|null $player4
 */
class GameHokm extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_hokm';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'player_1_id',
        'player_1_token',
        'player_1_quote',
        'player_1_name',
        'player_2_id',
        'player_2_token',
        'player_2_quote',
        'player_2_name',
        'player_3_id',
        'player_3_token',
        'player_3_quote',
        'player_3_name',
        'player_4_id',
        'player_4_token',
        'player_4_quote',
        'player_4_name',
        'data',
        'winners',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'winners' => 'array',
        'modified_at' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->modified_at = now()->getPreciseTimestamp();
        });
        static::updating(function ($model) {
            $model->modified_at = now()->getPreciseTimestamp();
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function player1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_1_id');
    }

    public function player2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_2_id');
    }

    public function player3(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_3_id');
    }

    public function player4(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_4_id');
    }
}
