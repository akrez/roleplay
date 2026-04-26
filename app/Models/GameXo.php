<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $owner_id
 * @property int|null $player_x_id
 * @property string|null $player_x_token
 * @property string|null $player_x_name
 * @property int|null $player_o_id
 * @property string|null $player_o_token
 * @property string|null $player_o_name
 * @property array|null $winners
 * @property array|null $data
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\User|null $playerX
 * @property-read \App\Models\User|null $playerO
 */
class GameXo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_xo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'player_x_id',
        'player_x_token',
        'player_x_name',
        'player_o_id',
        'player_o_token',
        'player_o_name',
        'status',
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
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function playerX(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_x_id');
    }

    public function playerO(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_o_id');
    }
}
