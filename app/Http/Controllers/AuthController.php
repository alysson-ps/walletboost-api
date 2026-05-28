<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\PersonalAccessToken\PersonalAccessTokenResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()->toResource()
        ]);
    }

    public function register(AuthRegisterRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        DB::commit();

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user->toResource()
        ], 201);
    }

    public function login(AuthLoginRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        /**
         * @var User
         */
        $user = $request->user();

        $token = $user->createToken(
            name: "user-token",
            expiresAt: Carbon::now()->addHours(2)
        )->plainTextToken;

        return response()->json([
            'user' => $user->toResource(),
            'token' => $token
        ]);
    }

    public function tokenVerify(Request $request)
    {
        /**
         * @var User
         */
        $user = $request->user();

        $token = $user->currentAccessToken();

        return response()->json([
            'success' => true,
            'token' => PersonalAccessTokenResource::make($token)
        ]);
    }
}
