<?php

namespace App\Http\Resources\GameHokm;

use App\Http\Resources\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Services\GameHokmService;
use Illuminate\Http\Request;

class GameHokmResource extends JsonResource
{
    const PURPOSE_MODIFICATION = 'MODIFICATION';

    const PURPOSE_INDEX = 'INDEX';

    const PURPOSE_FULL = 'FULL';

    protected ?int $userId = null;

    protected ?string $purpose = null;

    public function setUserId(int $userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function setPurpose(?string $purpose)
    {
        $this->purpose = $purpose;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(?Request $request = null)
    {
        $currentPlayerIndex = $this->getPlayer($this->userId);

        $result = [
            'id' => $this->id,
            'player_index' => $currentPlayerIndex,
            'modified_at' => $this->modified_at,
            'finished_at' => $this->finished_at,
        ];

        foreach ([GameHokmService::PLAYER_1, GameHokmService::PLAYER_2, GameHokmService::PLAYER_3, GameHokmService::PLAYER_4] as $playerIndex) {
            $result['player_quotes'][$playerIndex] = $this->{$playerIndex.'_quote'};
        }

        if (in_array($this->purpose, [null, self::PURPOSE_MODIFICATION])) {
            return $result;
        }

        $data = $this->resource->data;

        $result += [
            'winners' => $this->winners,
            'token' => $this->{$currentPlayerIndex.'_token'},
            'player' => (empty($data['players'][$currentPlayerIndex]) ? null : [
                'name' => $data['players'][$currentPlayerIndex]['name'],
                'username' => $data['players'][$currentPlayerIndex]['username'],
            ]),
        ];

        foreach ([GameHokmService::PLAYER_1, GameHokmService::PLAYER_2, GameHokmService::PLAYER_3, GameHokmService::PLAYER_4] as $playerIndex) {
            $result['players'][$playerIndex] = [
                'name' => $data['players'][$playerIndex]['name'],
            ];
        }

        if ($this->purpose == self::PURPOSE_INDEX) {
            $result['owner'] = (new UserResource($this->resource->owner));

            return $result;
        }

        $result += [
            'hand_turn' => $data['hand_turn'],
            'plays' => $data['plays'],
            'old_plays' => $data['old_plays'],
            'state' => $data['state'],
            'turn' => $data['turn'],
            'hand' => $data['hand'],
        ];
        $playerDeck = $data['deck'][$currentPlayerIndex];
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
