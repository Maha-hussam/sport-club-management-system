<?php

namespace App\Actions;

use App\Events\PlayerAssignedToMatch;
use App\Models\GameMatch;
use Illuminate\Support\Facades\DB;

class AssignPlayerToMatch
{
    public function handle(GameMatch $gameMatch, array $playerIds): void
    {
        $newPlayers = DB::transaction(function () use ($gameMatch, $playerIds) {
            $currentPlayerIds = $gameMatch->players()
                ->pluck('users.id')
                ->all();

            $newPlayerIds = array_diff($playerIds, $currentPlayerIds);

            $gameMatch->players()->sync($playerIds);

            return $gameMatch->players()
                ->whereIn('users.id', $newPlayerIds)
                ->get();
        });

        foreach ($newPlayers as $player) {
            PlayerAssignedToMatch::dispatch($player, $gameMatch);
        }
    }
}