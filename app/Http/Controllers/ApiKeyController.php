<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiKeyStoreRequest;
use App\Http\Resources\PersonalAccessToken\PersonalAccessTokenResource;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    public function index(Request $request)
    {
        $apiKeys = $request->user()
            ->tokens()
            ->whereJsonContains('abilities', 'api-key')
            ->get();

        return response()->json([
            'success' => true,
            'data' => PersonalAccessTokenResource::collection($apiKeys),
        ]);
    }

    public function store(ApiKeyStoreRequest $request)
    {
        $token = $request->user()->createToken(
            name: $request->validated('name'),
            abilities: ['api-key'],
        );

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token->plainTextToken,
                'api_key' => PersonalAccessTokenResource::make($token->accessToken),
            ],
        ], 201);
    }

    public function destroy(int $id, Request $request)
    {
        $request->user()
            ->tokens()
            ->whereJsonContains('abilities', 'api-key')
            ->findOrFail($id)
            ->delete();

        return response()->noContent();
    }
}
