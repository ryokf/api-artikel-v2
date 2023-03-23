<?php

namespace App\Http\Controllers;

use app\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    function register(Request $request)
    {
        $request->validate([
            'username' => 'required|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        $dataUser = [
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'photo' => $request->photo,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        User::create($dataUser);

        if (User::where('email', $request->email)->first() == null) {
            return ResponseFormatter::response(404, 'error', "token tidak ditemukan");
        }

        return ResponseFormatter::response(200, 'success', $dataUser);
    }


    function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $dataLogin = [
            'user' => User::where('email', $request->email)?->first(),
            'token' => "Bearer " . $user->createToken($request->email)->plainTextToken
        ];

        if ($dataLogin['user'] == null) {
            return ResponseFormatter::response(404, 'error', "data tidak ditemukan");
        }

        return ResponseFormatter::response(200, 'success', $dataLogin);
    }


    function logout(Request $request)
    {
        $data = PersonalAccessToken::findToken($request->bearerToken());

        if ($data == null) {
            return ResponseFormatter::response(404, 'error', "token tidak ditemukan");
        }

        $data->delete();

        return ResponseFormatter::response(404, 'success', $data);
    }
}
