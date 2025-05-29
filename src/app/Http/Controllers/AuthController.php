<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;
class AuthController extends Controller
{
    // POST /api/v1/register
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string',
            'cpf_cnpj' => 'nullable|string|unique:users',
            'role' => 'in:admin,producer,client'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'cpf_cnpj' => $data['cpf_cnpj'] ?? null,
            'role' => $data['role'],
        ]);

        $token = $user->createToken('API Token')->accessToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    // POST /api/v1/login
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('API Token')->accessToken;
        return response()->json(['user' => $user, 'token' => $token]);
    }

    // opcional: POST /api/v1/logout
    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();
        app(TokenRepository::class)->revokeAccessToken($accessToken->id);
        app(RefreshTokenRepository::class)->revokeRefreshTokensByAccessTokenId($accessToken->id);
        return response()->json(['message' => 'Logged out']);
    }

    // opcional: GET /api/v1/user
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
