<?php

namespace App\Http\Resources\UserFriendship;

use App\Http\Resources\ResourceCollection;
use Illuminate\Http\Request;

class UserFriendshipCollection extends ResourceCollection
{
    protected ?int $userId = null;

    public function setUserId(int $userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(?Request $request = null)
    {
        $result = [];
        foreach ($this->collection as $userFriendshipResource) {
            $result[] = $userFriendshipResource->setUserId($this->userId)->toArray($request);
        }

        return $result;
    }
}
