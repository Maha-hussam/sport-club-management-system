<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index()
    {
        $matches = GameMatch::latest('match_date')->paginate(10);

        return view('trainer.matches.index', compact('matches'));
    }

    public function create()
    {
        return view('trainer.matches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'match_name' => ['required', 'string', 'max:255'],
            'match_date' => ['required', 'date'],
            'match_time' => ['required', 'date_format:H:i'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

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
    public function updatePlayers(Request $request, GameMatch $gameMatch)
    {
        $validated = $request->validate([
            'players' => ['sometimes', 'array'],
            'players.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('users', 'id')
                    ->where('role', 'player'),
            ],
        ]);

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

    public function update(Request $request, GameMatch $gameMatch)
    {
        $validated = $request->validate([
            'match_name' => ['required', 'string', 'max:255'],
            'match_date' => ['required', 'date'],
            'match_time' => ['required', 'date_format:H:i'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

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