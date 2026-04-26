<?php

namespace App\Http\Resources\GameHokm;

use App\Http\Resources\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Services\GameHokmService;
use Illuminate\Http\Request;

class GameHokmResource extends JsonResource
{
    protected bool $withOwner = false;

    protected bool $withData = false;

    protected ?int $userId = null;

    public function setUserId(int $userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function withData($withData = true)
    {
        $this->withData = $withData;

        return $this;
    }

    public function withOwner($withOwner = true)
    {
        $this->withOwner = $withOwner;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(?Request $request = null)
    {
        $result = [
            'id' => $this->id,
            'winners' => $this->winners,
            'modified_at' => $this->modified_at,
        ];

        $currentPlayer = $this->getPlayer($this->userId);

        $result['player'] = $currentPlayer;

        foreach ([GameHokmService::PLAYER_1, GameHokmService::PLAYER_2, GameHokmService::PLAYER_3, GameHokmService::PLAYER_4] as $player) {
            $result['players'][$player] = [
                'id' => $this->{$player.'_id'},
                'name' => $this->{$player.'_name'},
                'token' => ($player == $currentPlayer ? $this->{$player.'_token'} : null),
                'quote' => $this->{$player.'_quote'},
            ];
        }

        if ($this->withOwner) {
            $result['owner'] = (new UserResource($this->resource->owner));
        }

        if ($this->withData) {
            $data = $this->resource->data;
            $result += [
                'hand_turn' => $data['hand_turn'],
                'plays' => $data['plays'],
                'old_plays' => $data['old_plays'],
                'state' => $data['state'],
                'turn' => $data['turn'],
                'hand' => $data['hand'],
            ];
            $playerDeck = $data['deck'][$currentPlayer];
            if (empty($data['turn']['suit'])) {
                usort($playerDeck, function ($a, $b) {
                    return $a['random_id'] <=> $b['random_id'];
                });
                $playerDeck = array_slice($playerDeck, 0, 5);
                usort($playerDeck, function ($a, $b) {
                    return $a['id'] <=> $b['id'];
                });
            }
            $result['player_deck'] = array_values($playerDeck);
        }

        return $result;
    }

    protected function getPlayer(?int $userId)
    {
        foreach ([GameHokmService::PLAYER_1, GameHokmService::PLAYER_2, GameHokmService::PLAYER_3, GameHokmService::PLAYER_4] as $player) {
            if ($this->{$player.'_id'} == $userId) {
                return $player;
            }
        }

        return null;
    }
}
