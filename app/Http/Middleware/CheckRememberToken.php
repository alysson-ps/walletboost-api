<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\RememberToken;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckRememberToken
{
    public function handle(Request $request, Closure $next): Response
    {
        // Bearer token válido — segue normalmente
        if (Auth::guard('sanctum')->check()) {
            Auth::shouldUse('sanctum');
            return $next($request);
        }

        $cookieValue = $request->cookie('remember_me');

        if (! $cookieValue || ! str_contains((string) $cookieValue, ':')) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Split-token: "{id}:{raw_token}"
        [$id, $rawToken] = explode(':', (string) $cookieValue, 2);

        $record = RememberToken::with('user')->find((int) $id);

        if (! $record || $record->isExpired() || ! Hash::check($rawToken, $record->token_hash)) {
            return response()->json(['message' => 'Unauthenticated.'], 401)
                ->withCookie(cookie()->forget('remember_me'));
        }

        $user = $record->user;

        // Rotaciona o remember token a cada uso (previne roubo de sessão)
        $newRawToken = Str::random(64);
        $record->update([
            'token_hash' => Hash::make($newRawToken),
            'expires_at' => now()->addDays(30),
        ]);
        $newCookieValue = $record->id . ':' . $newRawToken;

        // Emite novo Sanctum token para o frontend armazenar
        $newSanctumToken = $user->createToken(
            name: 'user-token',
            expiresAt: Carbon::now()->addHours(2),
        )->plainTextToken;

        // Autentica o usuário para esta request
        Auth::guard('sanctum')->setUser($user);
        Auth::shouldUse('sanctum');

        return $next($request)
            ->withCookie(cookie(
                name: 'remember_me',
                value: $newCookieValue,
                minutes: 60 * 24 * 30,
                path: '/',
                secure: true,
                httpOnly: true,
                sameSite: 'lax',
            ))
            ->header('X-New-Token', $newSanctumToken);
    }
}
