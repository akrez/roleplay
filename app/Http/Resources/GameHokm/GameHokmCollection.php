<?php

namespace App\Http\Resources\GameHokm;

use App\Http\Resources\ResourceCollection;
use Illuminate\Http\Request;

class GameHokmCollection extends ResourceCollection
{
    protected ?string $purpose = null;

    protected ?int $userId = null;

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

    public function toArray(?Request $request = null)
    {
        $result = [];
        foreach ($this->collection as $gameHokmResource) {
            $result[] = $gameHokmResource->setPurpose($this->purpose)->setUserId($this->userId)->toArray($request);
        }

        return $result;
    }
}
