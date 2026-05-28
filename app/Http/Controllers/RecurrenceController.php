<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RecurrenceStoreRequest;
use App\Http\Requests\RecurrenceUpdateRequest;
use App\Models\Recurrence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class RecurrenceController extends Controller
{
    public function index(Request $request)
    {
        $recurrences = Recurrence::where('user_id', $request->user()->id)->get();

        return response()->json([
            'success' => true,
            'data' => $recurrences->toResourceCollection(),
        ]);
    }

    public function store(RecurrenceStoreRequest $request)
    {
        DB::beginTransaction();

        $recurrence = Recurrence::create(
            array_merge(['user_id' => $request->user()->id], $request->validated())
        );

        DB::commit();

        return response()->json([
            'success' => true,
            'data' => $recurrence->toResource(),
        ], 201);
    }

    public function update(int $id, RecurrenceUpdateRequest $request)
    {
        $recurrence = Recurrence::where('user_id', $request->user()->id)->findOrFail($id);

        DB::beginTransaction();

        $recurrence->update($request->validated());

        DB::commit();

        return response()->json([
            'success' => true,
            'data' => $recurrence->toResource(),
        ]);
    }

    public function destroy(int $id, Request $request)
    {
        $recurrence = Recurrence::where('user_id', $request->user()->id)->findOrFail($id);

        DB::beginTransaction();

        $recurrence->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Recurrence deleted successfully',
        ]);
    }
}
