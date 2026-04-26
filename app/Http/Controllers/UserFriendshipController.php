<?php

namespace App\Http\Controllers;

use App\Services\UserFriendshipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFriendshipController extends Controller
{
    public function index()
    {
        $fromUser = Auth::user();
        $response = UserFriendshipService::new()->getApiCollection($fromUser);

        return view('user_friendship.index', [
            'user' => $response->getData('user'),
            'user_friendships' => $response->getData('user_friendships'),
        ]);
    }

    public function store(Request $request)
    {
        $fromUser = Auth::user();
        UserFriendshipService::new()->create($fromUser->id, $request->username);

        return redirect()->back();
    }

    public function status($id, $status)
    {
        $fromUser = Auth::user();
        UserFriendshipService::new()->status($id, $fromUser->id, $status);

        return redirect()->back();
    }

    public function destroy($id)
    {
        $fromUser = Auth::user();
        UserFriendshipService::new()->delete($id, $fromUser->id);

        return redirect()->back();
    }
}
