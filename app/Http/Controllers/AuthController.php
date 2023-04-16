<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\VerifEmail;
use Illuminate\Http\Request;
use app\Helpers\ResponseFormatter;
use App\Jobs\SendEmailVerifQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Validation\ValidationException;

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

    public function verifyEmail(Request $request)
    {
        $hashedEmail = Hash::make(auth()->user()->email);
        $email = $request->user()->email;
        $username = auth()->user()->username;

        SendEmailVerifQueue::dispatch($email, $hashedEmail, $username);

        return ResponseFormatter::response(200, 'success', 'email sudah terkirim');
    }

    function verifyProcess(Request $request, $email)
    {
        if (count($request->all()) != 0) {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                // $request->session()->regenerate();

                $getId = User::where('email', $request->email)->get('id');

                Auth::loginUsingId($getId);

                if (!Hash::check($request->email, $email)) {
                    return 'verifikasi gagal, silahkan ulangi lagi!';
                }

                User::where('email', $request->email)->update([
                    'email_verified_at' => date('Y-m-d H:i:s')
                ]);

                return view('auth.verifySucess');
            }

            Session::flash('error', 'email atau password anda tidak sesuai');
        }

        if (!Auth::check()) {
            return view('auth.login_page', ['email' => $email]);
        }
    }

    function forgetPassword(Request $request)
    {
        $request->validate([
            'old' => ['required'],
            'new' => ['required'],
            'confirm' => ['required'],
        ]);

        if (!Hash::check($request->old, auth()->user()->password)) {
            return ResponseFormatter::response(200, 'error', 'password lama tidak sesuai');
        }

        if ($request->new != $request->confirm) {
            return ResponseFormatter::response(200, 'error', 'password konfirmasi tidak sama');
        }

        User::where('id', auth()->user()->id)->update([
            'password' => Hash::make($request->new)
        ]);

        return ResponseFormatter::response(200, 'success', 'password berhasil dirubah');
    }
}
