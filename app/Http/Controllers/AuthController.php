<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $response = [
            'user' => $user,
            'token' => $user->createToken('user-token')->plainTextToken,
        ];

        return response($response);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            $response = [
                'user' => $user,
                'token' => $user->createToken('user-token')->plainTextToken,
            ];
            return response($response);

        } else {
            return response( ['error' => 'Bad credentials'], 401);
        }
    }

    public function logout(){
        Auth::user()->tokens()->delete();
        return [
            'message'=>'logged out'
        ];
    }
}
