<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class PlayerController extends Controller
{
    public function index()
    {
        $players = User::where('role', 'player')->get();

        return PlayerResource::collection($players);
    }

    public function show(string $id)
    {
        $player = User::where('role', 'player')->findOrFail($id);

        return new PlayerResource($player);
    }

    public function store(StorePlayerRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['role'] = 'player';

        $player = User::create($data);

        return (new PlayerResource($player))
            ->response()
            ->setStatusCode(201);
    }

    public function update(
        UpdatePlayerRequest $request,
        string $id
    ): PlayerResource {
        $player = User::where('role', 'player')->findOrFail($id);

        $player->update($request->validated());

        return new PlayerResource($player->fresh());
    }

    public function destroy(string $id): JsonResponse
    {
        abort_unless(request()->user()?->role === 'trainer', 403);

        $player = User::where('role', 'player')->findOrFail($id);

        $player->delete();

        return response()->json([
            'message' => 'Player deleted successfully.',
        ], 200);
    }
}
