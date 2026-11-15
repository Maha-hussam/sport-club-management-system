<?php

namespace App\Listeners;

use App\Events\PlayerRegistered;
use Illuminate\Support\Facades\Log;

class LogPlayerRegistration
{
    public function handle(PlayerRegistered $event): void
    {
        $player = $event->player;

        Log::info('New player registered.', [
            'player_id' => $player->id,
            'player_name' => trim(
                $player->first_name . ' ' . $player->last_name
            ),
            'player_email' => $player->email,
        ]);
    }
}