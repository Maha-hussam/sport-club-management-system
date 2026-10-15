<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlayerController extends Controller
{
    public function index(): View
    {
        $players = User::where('role', 'player')
            ->latest()
            ->paginate(10);

        return view('trainer.players.index', compact('players'));
    }

    public function create(): View
    {
        return view('trainer.players.create');
    }

    public function store(
        StorePlayerRequest $request
    ): RedirectResponse {
        User::create([
            'first_name' => $request->validated('first_name'),
            'last_name' => $request->validated('last_name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'role' => 'player',
        ]);

        return redirect()
            ->route('trainer.players.index')
            ->with('success', 'Player added successfully.');
    }

    public function edit(User $player): View
    {
        $player = $this->findPlayer($player);

        return view('trainer.players.edit', compact('player'));
    }

    public function update(
        UpdatePlayerRequest $request,
        User $player
    ): RedirectResponse {
        $player = $this->findPlayer($player);

        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $player->update($data);

        return redirect()
            ->route('trainer.players.index')
            ->with('success', 'Player updated successfully.');
    }

    public function destroy(User $player): RedirectResponse
    {
        $player = $this->findPlayer($player);

        $player->delete();

        return redirect()
            ->route('trainer.players.index')
            ->with('success', 'Player deleted successfully.');
    }

    private function findPlayer(User $user): User
    {
        abort_unless($user->role === 'player', 404);

        return $user;
    }
}