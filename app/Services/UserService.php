<?php

namespace App\Services;

use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Support\ApiResponse;

class UserService extends Service
{
    public static function new()
    {
        return app(self::class);
    }

    public function filter($username = null, $nameOrUsernameFilter = null, $limit = null)
    {
        $usersQuery = User::query();

        if ($username) {
            $usersQuery->where('username', '!=', $username);
        }

        if ($nameOrUsernameFilter) {
            $usersQuery->where(function ($query) use ($nameOrUsernameFilter) {
                $query
                    ->orWhere('name', 'LIKE', '%'.$nameOrUsernameFilter.'%')
                    ->orWhere('username', 'LIKE', '%'.$nameOrUsernameFilter.'%');
            });
        }

        if ($limit) {
            $usersQuery->limit($limit);
        }

        return ApiResponse::new(200)->data([
            'users' => (new UserCollection($usersQuery->get()))->toArray(),
        ]);
    }

    public function findByUsername($username)
    {
        $user = User::where('username', $username)->first();
        if (! $user) {
            return ApiResponse::new(404);
        }

        return ApiResponse::new(200)->data([
            'user' => (new UserResource($user)),
        ]);
    }

    public function friends(User $user, $filterUsernames = [])
    {
        $friendsQuery = $user->friends();
        if ($filterUsernames) {
            $friendsQuery->whereIn('username', $filterUsernames);
        }

        return ApiResponse::new(200)->data([
            'users' => (new UserCollection($friendsQuery->get()->prepend($user)))->toArray(),
        ]);
    }
}
