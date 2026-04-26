<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function filter(Request $request)
    {
        $user = Auth::user();

        return UserService::new()->filter(
            $user->username,
            $request->input('filter'),
            5
        );
    }
}
