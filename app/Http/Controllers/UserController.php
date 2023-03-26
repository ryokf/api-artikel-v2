<?php

namespace App\Http\Controllers;

use app\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    function show(Request $request)
    {
        $user = $request?->user() ?? false;

        if (!$user) {
            return ResponseFormatter::response(404, 'error', 'user tidak ditemukan');
        }

        return ResponseFormatter::response(200, 'success', $user);
    }

    function update(Request $request)
    {
        return 'hello juga';
    }
}
