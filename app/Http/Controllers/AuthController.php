<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\PersonalAccessToken\PersonalAccessTokenResource;
use App\Models\RememberToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()->toResource(),
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
            'user' => $user->toResource(),
        ], 201);
    }

    public function login(AuthLoginRequest $request)
    {
        $validated = $request->validated();

        if (! Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        /** @var User */
        $user = Auth::user();

        $token = $user->createToken(
            name: 'user-token',
            expiresAt: Carbon::now()->addHours(2),
        )->plainTextToken;

        $response = response()->json([
            'user' => $user->toResource(),
            'token' => $token,
        ]);

        if ($request->boolean('remember_me')) {
            $rawToken = Str::random(64);

            $record = RememberToken::create([
                'user_id' => $user->id,
                'token_hash' => Hash::make($rawToken),
                'expires_at' => now()->addDays(30),
            ]);

            // Cookie carrega "{id}:{token}" — lookup por ID, verificação por hash
            $cookieValue = $record->id . ':' . $rawToken;

            $response = $response->withCookie(cookie(
                name: 'remember_me',
                value: $cookieValue,
                minutes: 60 * 24 * 30,
                path: '/',
                secure: true,
                httpOnly: true,
                sameSite: 'lax',
            ));
        }

        return $response;
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user?->currentAccessToken()?->delete();

        $cookieValue = $request->cookie('remember_me');

        if ($cookieValue && str_contains((string) $cookieValue, ':')) {
            [$id] = explode(':', (string) $cookieValue, 2);

            RememberToken::where('id', (int) $id)
                ->when($user, fn ($q) => $q->where('user_id', $user->id))
                ->delete();
        }

        return response()
            ->json(['message' => 'Logged out successfully'])
            ->withCookie(cookie()->forget('remember_me'));
    }

    public function tokenVerify(Request $request)
    {
        /** @var User */
        $user = $request->user();

        $token = $user->currentAccessToken();

        return response()->json([
            'success' => true,
            'token' => PersonalAccessTokenResource::make($token),
        ]);
    }
}
