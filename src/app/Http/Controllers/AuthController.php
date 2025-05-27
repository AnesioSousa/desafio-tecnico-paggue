<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // POST /api/v1/register
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // cria token de acesso
        $token = $user->createToken('authToken')->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // POST /api/v1/login
    public function login(Request $request)
    {
        $creds = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($creds)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $token = $user->createToken('authToken')->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    // opcional: POST /api/v1/logout
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logged out'], 200);
    }

    // opcional: GET /api/v1/user
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
