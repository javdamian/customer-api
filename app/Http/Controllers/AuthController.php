<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        dd($request->all());
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $this->generateToken($user);
            $this->storeToken($user->id, $token);

            return response()->json(['token' => $token]);
        }

        throw ValidationException::withMessages([
            'email' => ['Las credenciales son incorrectas'],
        ]);
    }

    private function generateToken($user)
    {
        $email = $user->email;
        $timestamp = now()->toDateTimeString();
        $random = mt_rand(200, 500);

        $stringToHash = $email . $timestamp . $random;
        $hashedString = sha1($stringToHash);

        return $hashedString;
    }

    private function storeToken($userId, $token)
    {
        DB::table('tokens')->insert([
            'user_id' => $userId,
            'tokedn' => $token,
            'created_at' => now(),
        ]);
    }
}