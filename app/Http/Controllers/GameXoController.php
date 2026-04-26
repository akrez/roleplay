<?php

namespace App\Http\Controllers;

use App\Services\GameXoService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameXoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $apiCollectionResponse = GameXoService::new()->getApiCollection($user);
        $friendsResponse = UserService::new()->friends($user);

        return view('game_xo.index', [
            'user' => $apiCollectionResponse->getData('user'),
            'friends' => $friendsResponse->getData('users'),
            'game_xos' => $apiCollectionResponse->getData('game_xos'),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        GameXoService::new()->create($user, $request->input('player_x_username'), $request->input('player_o_username'));

        return redirect()->back();
    }

    public function board(Request $request, $id, $player, $token)
    {
        $boardResponse = GameXoService::new()->board($id, $player, $token);
        if (! $boardResponse->isSuccessful()) {
            return redirect()->back();
        }

        return view('game_xo.board', [
            'game_xo' => $boardResponse->getData('game_xo'),
        ]);
    }
}
