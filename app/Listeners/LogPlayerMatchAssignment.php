<?php

namespace App\Listeners;

use App\Events\PlayerAssignedToMatch;
use App\Notifications\PlayerAssignedToMatchNotification;
use Illuminate\Support\Facades\Log;

class LogPlayerMatchAssignment
{
    public function handle(PlayerAssignedToMatch $event): void
    {
        Log::info('Player assigned to match.', [
            'player_name' => $event->player->first_name . ' ' . $event->player->last_name,
            'player_email' => $event->player->email,
            'match_name' => $event->match->match_name,
        ]);

        $event->player->notify(
            new PlayerAssignedToMatchNotification($event->match)
        );
    }
}