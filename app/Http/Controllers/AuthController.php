<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => 'User registered successfully!',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Registration failed. Please try again.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            return response()->json(['token' => $token]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Login failed. Please try again.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function user(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            return response()->json($user);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve user information.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
