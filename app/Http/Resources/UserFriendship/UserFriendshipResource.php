<?php

namespace App\Http\Resources\UserFriendship;

use App\Enums\UserFriendshipStatusEnum;
use App\Http\Resources\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Services\UserFriendshipService;
use Illuminate\Http\Request;

class UserFriendshipResource extends JsonResource
{
    protected int $userId;

    public function setUserId(int $userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(?Request $request = null)
    {
        return [
            'id' => $this->id,
            'from' => (new UserResource($this->from)),
            'to' => (new UserResource($this->to)),
            'status' => $this->formatEnum($this->status),
            //
            'can_block' => UserFriendshipService::new()->canStatus($this->userId, $this->resource, UserFriendshipStatusEnum::BLOCKED->name)->isSuccessful(),
            'can_accept' => UserFriendshipService::new()->canStatus($this->userId, $this->resource, UserFriendshipStatusEnum::ACCEPTED->name)->isSuccessful(),
            'can_delete' => UserFriendshipService::new()->canDelete($this->userId, $this->resource)->isSuccessful(),
        ];
    }
}
