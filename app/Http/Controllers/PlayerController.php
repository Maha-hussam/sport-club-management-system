<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class PlayerController extends Controller
{

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'registered_from' => ['nullable', 'date'],
            'registered_to' => [
                'nullable',
                'date',
                'after_or_equal:registered_from',
            ],
            'matches_count' => ['nullable', 'integer', 'min:0'],
            'sort_by' => ['nullable', 'in:first_name,created_at'],
            'sort_direction' => ['nullable', 'in:asc,desc'],
        ]);

        $query = User::where('role', 'player')
            ->withCount('matches')
            ->search($filters['search'] ?? null)
            ->registeredBetween(
                $filters['registered_from'] ?? null,
                $filters['registered_to'] ?? null
            )
            ->hasMatchesCount(
                isset($filters['matches_count'])
                ? (int) $filters['matches_count']
                : null
            );

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        $players = $query
            ->orderBy($sortBy, $sortDirection)
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('trainer.players.index', compact('players', 'filters'));
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