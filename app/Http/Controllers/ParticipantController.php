<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParticipantStoreRequest;
use App\Http\Requests\ParticipantUpdateRequest;
use App\Models\Participant;
use App\Repositories\Contracts\ParticipantRepositoryInterface;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        $participants = Participant::where('user_id', $request->user()->id)->get();

        return response()->json([
            'success' => true,
            'data' => $participants->toResourceCollection(),
        ]);
    }

    public function store(ParticipantStoreRequest $request)
    {
        $participant = Participant::create(
            array_merge(['user_id' => $request->user()->id], $request->validated())
        );

        return response()->json([
            'success' => true,
            'data' => $participant->toResource(),
        ]);
    }

    public function destroy(int $id, Request $request)
    {
        $participant = Participant::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $participant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Participant deleted successfully',
        ]);
    }

    public function edit(int $id, Request $request)
    {
        $participant = Participant::where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'participant' => $participant->toResource(),
            ],
        ]);
    }

    public function update(int $id, ParticipantUpdateRequest $request)
    {
        $participant = Participant::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $participant->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $participant->toResource(),
        ]);
    }
}
