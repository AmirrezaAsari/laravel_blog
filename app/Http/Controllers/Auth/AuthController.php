<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\JwtHelper;


class AuthController extends Controller
{
    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $data = ['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password)];

        User::create($validated);
        return response()->json(['message' => 'User registered successfully.'], 200);
    }

    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'These credentials do not match our records.'], 404);
        }

        $payload = [
            'sub' => $user->id,
            'email' => $user->email,
            'exp' => time() + 86400, // expires in 24h
        ];

        $token = JwtHelper::encode($payload);

        return response()->json([
            'token' => $token
        ], 200);
    }
}
