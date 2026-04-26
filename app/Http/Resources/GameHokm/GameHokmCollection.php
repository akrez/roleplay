<?php

namespace App\Http\Resources\GameHokm;

use App\Http\Resources\ResourceCollection;
use Illuminate\Http\Request;

class GameHokmCollection extends ResourceCollection
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

    public function toArray(?Request $request = null)
    {
        $result = [];
        foreach ($this->collection as $gameHokmResource) {
            $result[] = $gameHokmResource->withOwner($this->withOwner)->setUserId($this->userId)->toArray($request);
        }

        return $result;
    }
}
