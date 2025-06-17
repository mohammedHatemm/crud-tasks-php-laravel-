<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        // Validate the request data
        $filed = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            'role' => 'nullable|in:admin,user',
        ]);

        $filed['password'] = Hash::make($filed['password']);
        // Create a new user
        $user = User::create($filed);
        $token = $user->createToken($request->username)->plainTextToken;

        // Return a response
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
    public function login(Request $request)
    {
        // Validate the request data
        $filed = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        // Check if the user exists and the password is correct
        $user = User::where('email', $filed['email'])->first();
        if (!$user || !Hash::check($filed['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Create a token for the user
        $token = $user->createToken($user->username)->plainTextToken;

        // Return a response
        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        // Revoke the user's token
        $request->user()->currentAccessToken()->delete();

        // Return a response
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
