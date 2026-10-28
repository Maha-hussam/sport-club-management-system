<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMatchRequest;
use App\Http\Requests\UpdateMatchRequest;
use App\Http\Resources\MatchResource;
use App\Models\GameMatch;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AssignPlayersRequest;
class MatchController extends Controller
{
    public function index()
    {
        $matches = GameMatch::with('players')->get();

        return MatchResource::collection($matches);
    }

    public function show(string $id)
    {
        $match = GameMatch::with('players')->findOrFail($id);

        return new MatchResource($match);
    }

    public function store(StoreMatchRequest $request)
    {
        $match = GameMatch::create($request->validated());

        return (new MatchResource($match->load('players')))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateMatchRequest $request, string $id)
    {
        $match = GameMatch::findOrFail($id);

        $match->update($request->validated());

        return new MatchResource($match->fresh()->load('players'));
    }

    public function assignPlayers(
        AssignPlayersRequest $request,
        string $id
    ) {
        $match = GameMatch::findOrFail($id);

        $match->players()->sync(
            $request->validated()['player_ids']
        );

        return new MatchResource(
            $match->fresh()->load('players')
        );
    }

    public function destroy(string $id): JsonResponse
    {
        abort_unless(request()->user()?->role === 'trainer', 403);

        $match = GameMatch::findOrFail($id);

        $match->players()->detach();
        $match->delete();

        return response()->json([
            'message' => 'Match deleted successfully.',
        ], 200);
    }
}