<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\User;
use App\Http\Requests\StoreMatchRequest;
use App\Http\Requests\UpdateMatchRequest;
use App\Http\Requests\AssignPlayersRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
class MatchController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'match_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'sort_by' => ['nullable', 'in:match_name,match_date'],
            'sort_direction' => ['nullable', 'in:asc,desc'],
        ]);

        $query = GameMatch::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('match_name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['match_date'])) {
            $query->whereDate('match_date', $filters['match_date']);
        }

        if (!empty($filters['location'])) {
            $query->where('location', 'like', '%' . $filters['location'] . '%');
        }

        $sortBy = $filters['sort_by'] ?? 'match_date';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        $matches = $query
            ->orderBy($sortBy, $sortDirection)
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('trainer.matches.index', compact('matches', 'filters'));
    }

    public function create()
    {
        return view('trainer.matches.create');
    }

    public function store(StoreMatchRequest $request)
    {
        $validated = $request->validated();
        GameMatch::create($validated);

        return redirect()
            ->route('trainer.matches.index')
            ->with('success', 'Match created successfully.');
    }

    public function show(GameMatch $gameMatch)
    {
        $gameMatch->load('players');

        $players = User::where('role', 'player')
            ->orderBy('first_name')
            ->get();

        return view('trainer.matches.show', [
            'match' => $gameMatch,
            'players' => $players,
        ]);
    }
    public function updatePlayers(
        AssignPlayersRequest $request,
        GameMatch $gameMatch
    ) {
        $validated = $request->validated();

        $playerIds = $validated['players'] ?? [];
        $gameMatch->players()->sync($playerIds);

        return redirect()
            ->route('trainer.matches.show', $gameMatch)
            ->with('success', 'Player assignments updated successfully.');
    }

    public function edit(GameMatch $gameMatch)
    {
        return view('trainer.matches.edit', [
            'match' => $gameMatch,
        ]);
    }

    public function update(
        UpdateMatchRequest $request,
        GameMatch $gameMatch
    ) {
        $validated = $request->validated();
        $gameMatch->update($validated);
        return redirect()
            ->route('trainer.matches.index')
            ->with('success', 'Match updated successfully.');
    }

    public function destroy(GameMatch $gameMatch)
    {
        $gameMatch->delete();
        return redirect()
            ->route('trainer.matches.index')
            ->with('success', 'Match deleted successfully.');
    }
}