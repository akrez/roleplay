<?php

namespace App\Http\Resources\GameXo;

use App\Http\Resources\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Services\GameXoService;
use Illuminate\Http\Request;

class GameXoResource extends JsonResource
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
        $data = [
            'id' => $this->id,
            'player' => $this->getPlayer(),
            GameXoService::PLAYER_X => [
                'id' => $this->player_x_id,
                'name' => $this->player_x_name,
                'token' => (
                    $this->player_x_id == $this->userId ?
                    $this->player_x_token :
                    null
                ),
            ],
            GameXoService::PLAYER_O => [
                'id' => $this->player_o_id,
                'name' => $this->player_o_name,
                'token' => (
                    $this->player_o_id == $this->userId ?
                    $this->player_o_token :
                    null
                ),
            ],
            'winners' => $this->winners,
        ];

        if ($this->withOwner) {
            $data['owner'] = (new UserResource($this->resource->owner));
        }

        if ($this->withData) {
            $data['data'] = $this->resource->data;
        }

        return $data;
    }

    protected function getPlayer()
    {
        if ($this->player_x_id == $this->userId) {
            return GameXoService::PLAYER_X;
        }

        if ($this->player_o_id == $this->userId) {
            return GameXoService::PLAYER_O;
        }

        return null;
    }
}
