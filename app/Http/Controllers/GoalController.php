<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GoalStoreRequest;
use App\Http\Requests\GoalUpdateRequest;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class GoalController extends Controller
{
    public function index(Request $request)
    {
        $goals = Goal::where('user_id', $request->user()->id)->get();

        return response()->json([
            'success' => true,
            'data' => $goals->toResourceCollection(),
        ]);
    }

    public function store(GoalStoreRequest $request)
    {
        DB::beginTransaction();

        $goal = Goal::create(
            array_merge(['user_id' => $request->user()->id], $request->validated())
        );

        DB::commit();

        return response()->json([
            'success' => true,
            'data' => $goal->toResource(),
        ], 201);
    }

    public function update(int $id, GoalUpdateRequest $request)
    {
        $goal = Goal::where('user_id', $request->user()->id)->findOrFail($id);

        DB::beginTransaction();

        $goal->update($request->validated());

        DB::commit();

        return response()->json([
            'success' => true,
            'data' => $goal->toResource(),
        ]);
    }

    public function destroy(int $id, Request $request)
    {
        $goal = Goal::where('user_id', $request->user()->id)->findOrFail($id);

        DB::beginTransaction();

        $goal->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Goal deleted successfully',
        ]);
    }
}
