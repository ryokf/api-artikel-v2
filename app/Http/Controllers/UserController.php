<?php

namespace App\Http\Controllers;

use app\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $request->validate([
            'username' => 'required|max:255',
            'email' => 'required|email',
            'photo' => 'required',
            'first_name' => 'required',
        ]);

        $dataUser = [
            'id' => $request->user()->id,
            'username' => $request->username,
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'photo' => $request->photo,
        ];

        User::where('id', $request->user()->id)->update($dataUser);

        $updatedUser = User::where('id', $request->user()->id)->first();

        return ResponseFormatter::response(200, 'success', $updatedUser);
    }
}
