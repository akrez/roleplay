<?php

namespace App\Services;

use App\Enums\UserFriendshipStatusEnum;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\UserFriendship\UserFriendshipCollection;
use App\Http\Resources\UserFriendship\UserFriendshipResource;
use App\Models\User;
use App\Models\UserFriendship;
use App\Support\ApiResponse;

class UserFriendshipService extends Service
{
    public static function new()
    {
        return app(self::class);
    }

    public function getApiResource(int $id, int $userId): ApiResponse
    {
        $userFriendship = UserFriendship::where(function ($query) use ($userId) {
            $query->where('request_from', $userId)->orWhere('request_to', $userId);
        })->with(['from', 'to'])->find($id);
        if (! $userFriendship) {
            return ApiResponse::new(404);
        }

        return ApiResponse::new(200)->data([
            'user_friendship' => (new UserFriendshipResource($userFriendship))->setUserId($userId)->toArray(),
        ]);
    }

    public function getApiCollection(User $user): ApiResponse
    {
        $userId = $user->id;

        $userFriendships = UserFriendship::where(function ($query) use ($userId) {
            $query->where('request_from', $userId)->orWhere('request_to', $userId);
        })->with(['from', 'to'])->orderBy('created_at', 'DESC')->get();

        return ApiResponse::new()->data([
            'user' => (new UserResource($user))->toArray(),
            'user_friendships' => (new UserFriendshipCollection($userFriendships))->setUserId($user->id)->toArray(),
        ]);
    }

    public function create(int $fromUserId, ?string $username)
    {
        if (! $username) {
            return ApiResponse::new(400);
        }

        $toUserResponse = UserService::new()->findByUsername($username);
        if (! $toUserResponse->isSuccessful()) {
            return $toUserResponse;
        }

        $toUserId = $toUserResponse->getData('user.id');

        if ($fromUserId === $toUserId) {
            return ApiResponse::new(400);
        }

        $existing = UserFriendship::where('request_from', $fromUserId)
            ->where('request_to', $toUserId)
            ->first();
        if ($existing) {
            return ApiResponse::new(409);
        }

        $friendship = UserFriendship::create([
            'request_from' => $fromUserId,
            'request_to' => $toUserId,
            'status' => UserFriendshipStatusEnum::PENDING->name,
        ]);

        return ApiResponse::new()->data([
            'user_friendship' => (new UserFriendshipResource($friendship))->setUserId($fromUserId)->toArray(),
        ]);
    }

    public function canStatus(int $userId, UserFriendship $userFriendship, $status)
    {
        if ($userFriendship->request_to != $userId) {
            return ApiResponse::new(403);
        }

        if (! in_array($status, UserFriendshipStatusEnum::names())) {
            return ApiResponse::new(400);
        }

        return ApiResponse::new();
    }

    public function status(int $id, int $userId, string $status)
    {
        $userFriendship = UserFriendship::find($id);

        $canResponse = $this->canStatus($userId, $userFriendship, $status);
        if (! $canResponse->isSuccessful()) {
            return $canResponse;
        }

        $userFriendship->update(['status' => $status]);

        return ApiResponse::new();
    }

    public function canDelete(int $userId, UserFriendship $userFriendship)
    {
        if ($userFriendship->request_to == $userId) {
            return ApiResponse::new();
        }

        if ($userFriendship->request_from == $userId) {
            if (in_array($userFriendship->status->name, [
                UserFriendshipStatusEnum::PENDING->name,
                UserFriendshipStatusEnum::ACCEPTED->name,
            ])) {
                return ApiResponse::new();
            }
        }

        return ApiResponse::new(403);
    }

    public function delete(int $id, int $userId)
    {
        $userFriendship = UserFriendship::find($id);

        $canResponse = $this->canDelete($userId, $userFriendship);
        if (! $canResponse->isSuccessful()) {
            return $canResponse;
        }

        $userFriendship->delete();

        return ApiResponse::new();
    }
}
