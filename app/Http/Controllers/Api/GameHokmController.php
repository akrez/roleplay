<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GameHokmService;
use Illuminate\Http\Request;

class GameHokmController extends Controller
{
    public function modification(Request $request, $id, $player, $token)
    {
        return GameHokmService::new()->modification($id, $player, $token);
    }

    public function quote(Request $request, $id, $player, $token)
    {
        return GameHokmService::new()->quote($id, $player, $token, $request->input('quote'));
    }

    public function board(Request $request, $id, $player, $token)
    {
        return GameHokmService::new()->board($id, $player, $token);
    }

    public function play(Request $request, $id, $player, $token)
    {
        return GameHokmService::new()->play($id, $player, $token, $request->input('card'));
    }
}
