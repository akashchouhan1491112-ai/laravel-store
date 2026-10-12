<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthApiController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(6)],
            'role' => ['nullable', 'string', 'in:customer,vendor'],
            'phone' => ['nullable', 'string', 'max:20'],
            'store_name' => ['required_if:role,vendor', 'nullable', 'string', 'max:255', 'unique:stores,name'],
        ]);

        $role = $request->input('role', 'customer');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'phone' => $request->phone,
            'status' => 'active',
        ]);

        if ($role === 'vendor') {
            $storeName = $request->input('store_name', $user->name . "'s Store");
            Store::create([
                'user_id' => $user->id,
                'name' => $storeName,
                'slug' => Str::slug($storeName) . '-' . Str::random(5),
                'status' => 'pending',
                'commission_rate' => 10.00,
                'balance' => 0.00,
                'total_sales' => 0.00,
            ]);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'user' => $user->load('store'),
            'token' => $token,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid email or password.'], 401);
        }

        if ($user->status === 'suspended') {
            return response()->json(['message' => 'Account is suspended.'], 403);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'user' => $user->load('store'),
            'token' => $token,
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user()->load('store'),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
