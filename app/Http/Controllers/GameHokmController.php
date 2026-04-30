<?php

namespace App\Http\Controllers;

use App\Services\GameHokmService;
use App\Services\UserService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameHokmController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->expectsJson()) {
            return view('game_hokm.create');
        }

        $user = Auth::user();
        $apiCollectionResponse = GameHokmService::new()->index($user, $request->query('page'));
        $friendsResponse = UserService::new()->friends($user);

        return ApiResponse::new()->data([
            'user' => $apiCollectionResponse->getData('user'),
            'friends' => $friendsResponse->getData('users'),
            'game_hokms' => $apiCollectionResponse->getData('game_hokms'),
        ])->paginator($apiCollectionResponse->getPaginator());
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        return GameHokmService::new()->create(
            $user,
            $request->input('player_1_username'),
            $request->input('player_2_username'),
            $request->input('player_3_username'),
            $request->input('player_4_username'),
        );
    }

    public function board(Request $request, $id, $player, $token)
    {
        $boardResponse = GameHokmService::new()->board($id, $player, $token);
        if (! $boardResponse->isSuccessful()) {
            return redirect()->back();
        }

        return view('game_hokm.board', [
            'game_hokm' => $boardResponse->getData('game_hokm'),
        ]);
    }
}
