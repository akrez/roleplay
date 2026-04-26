<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GameXoService;
use Illuminate\Http\Request;

class GameXoController extends Controller
{
    public function board(Request $request, $id, $player, $token)
    {
        return GameXoService::new()->board($id, $player, $token);
    }

    public function play(Request $request, $id, $player, $token)
    {
        return GameXoService::new()->play($id, $player, $token, $request->input('row'), $request->input('col'));
    }
}
