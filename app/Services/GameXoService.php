<?php

namespace App\Services;

use App\Http\Resources\GameXo\GameXoCollection;
use App\Http\Resources\GameXo\GameXoResource;
use App\Http\Resources\User\UserResource;
use App\Models\GameXo;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Support\Str;

class GameXoService extends Service
{
    const PLAYER_X = 'player_x';

    const PLAYER_O = 'player_o';

    public static function new()
    {
        return app(self::class);
    }

    public function getApiCollection(User $user): ApiResponse
    {
        $gameXos = GameXo::where(function ($query) use ($user) {
            $query
                ->orWhere('player_x_id', $user->id)
                ->orWhere('player_o_id', $user->id)
                ->orWhere('owner_id', $user->id);
        })
            ->orderBy('created_at', 'DESC')
            ->get();

        return ApiResponse::new()->data([
            'user' => (new UserResource($user))->toArray(),
            'game_xos' => (new GameXoCollection($gameXos))->withOwner()->setUserId($user->id)->toArray(),
        ]);
    }

    public function create(User $user, $playerXUsername, $playerOUsername)
    {
        if (! $playerXUsername || ! $playerOUsername) {
            return ApiResponse::new(406);
        }

        if ($playerXUsername == $playerOUsername) {
            return ApiResponse::new(406);
        }

        $friends = collect(UserService::new()->friends($user, [$playerXUsername, $playerOUsername])->getData('users'))
            ->pluck(null, 'username')
            ->toArray();

        if (
            ! array_key_exists($playerXUsername, $friends) ||
            ! array_key_exists($playerOUsername, $friends)
        ) {
            return ApiResponse::new(406);
        }

        $gameXo = GameXo::create([
            'owner_id' => $user->id,
            'winners' => [],
            //
            'player_x_id' => $friends[$playerXUsername]['id'],
            'player_x_token' => Str::ulid(),
            'player_x_name' => $friends[$playerXUsername]['name'],
            //
            'player_o_id' => $friends[$playerOUsername]['id'],
            'player_o_token' => Str::ulid(),
            'player_o_name' => $friends[$playerOUsername]['name'],
            //
            'data' => [
                'board' => [
                    [null, null, null],
                    [null, null, null],
                    [null, null, null],
                ],
                'turn' => static::PLAYER_X,
                'moves' => 0,
            ],
        ]);

        return ApiResponse::new(201)->data([
            'game_xo' => (new GameXoResource($gameXo))->setUserId($user->id)->toArray(),
        ]);
    }

    public function board($id, $player, $token)
    {
        if (in_array($player, [static::PLAYER_X, static::PLAYER_O])) {
            $playerTokenColumn = $player.'_token';
            $playerIdColumn = $player.'_id';
        } else {
            return ApiResponse::new(406);
        }

        $gameXo = GameXo::where($playerTokenColumn, $token)->find($id);
        if (! $gameXo) {
            return ApiResponse::new(404);
        }

        return ApiResponse::new()->data([
            'game_xo' => (new GameXoResource($gameXo))->setUserId($gameXo->$playerIdColumn)->withData()->toArray(),
        ]);
    }

    public function play($id, $player, $token, int $row, int $col)
    {
        if (in_array($player, [static::PLAYER_X, static::PLAYER_O])) {
            $playerTokenColumn = $player.'_token';
            $playerIdColumn = $player.'_id';
        } else {
            return ApiResponse::new(406);
        }

        $gameXo = GameXo::where($playerTokenColumn, $token)->find($id);
        if (! $gameXo) {
            return ApiResponse::new(404);
        }

        if ($gameXo->winners) {
            return ApiResponse::new(406);
        }

        $data = $gameXo->data;

        if (array_key_exists($row, $data['board']) && array_key_exists($col, $data['board'][$row])) {
            //
        } else {
            return ApiResponse::new(408)->data($data);
        }

        if ($data['board'][$row][$col] !== null) {
            return ApiResponse::new(406);
        }
        if ($data['turn'] !== $player) {
            return ApiResponse::new(403);
        }

        $data['board'][$row][$col] = $player;
        $data['moves']++;

        if ($this->checkWinner($data['board'], $player)) {
            $gameXo->winners = [$player];
        } elseif ($data['moves'] === 9) {
            $gameXo->winners = [static::PLAYER_X, static::PLAYER_O];
        } else {
            $data['turn'] = ($player === static::PLAYER_X) ? static::PLAYER_O : static::PLAYER_X;
        }

        $gameXo->data = $data;
        $gameXo->save();

        return ApiResponse::new(200);
    }

    protected function checkWinner(array $board, string $player): ?string
    {
        for ($i = 0; $i < 3; $i++) {
            if ($board[$i][0] === $player && $board[$i][1] === $player && $board[$i][2] === $player) {
                return $player;
            }
            if ($board[0][$i] === $player && $board[1][$i] === $player && $board[2][$i] === $player) {
                return $player;
            }
        }

        if ($board[0][0] === $player && $board[1][1] === $player && $board[2][2] === $player) {
            return $player;
        }
        if ($board[0][2] === $player && $board[1][1] === $player && $board[2][0] === $player) {
            return $player;
        }

        return null;
    }
}
