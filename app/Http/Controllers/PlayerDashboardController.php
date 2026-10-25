<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlayerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $player = $request->user();

        $matches = $player->matches();

        $totalMatches = (clone $matches)->count();

        $upcomingMatches = (clone $matches)
            ->where(function ($query) {
                $query->whereDate('match_date', '>', today())
                    ->orWhere(function ($query) {
                        $query->whereDate('match_date', today())
                            ->where('match_time', '>=', now()->format('H:i:s'));
                    });
            })
            ->orderBy('match_date')
            ->orderBy('match_time')
            ->get();

        return view('player.dashboard', [
            'player' => $player,
            'totalMatches' => $totalMatches,
            'upcomingMatches' => $upcomingMatches,
        ]);
    }

    public function profile(Request $request)
    {
        $player = $request->user();

        return view('player.profile', [
            'player' => $player,
        ]);
    }

    public function matches(Request $request)
    {
        $player = $request->user();

        $matches = $player->matches()
            ->orderBy('match_date')
            ->orderBy('match_time')
            ->get();

        return view('player.matches', [
            'player' => $player,
            'matches' => $matches,
        ]);
    }

    public function showMatch(Request $request, int $matchId)
    {
        $player = $request->user();

        $match = $player->matches()
            ->whereKey($matchId)
            ->first();

        abort_unless($match, 403);

        return view('player.match-details', [
            'player' => $player,
            'match' => $match,
        ]);
    }

}