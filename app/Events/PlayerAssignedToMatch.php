<?php

namespace App\Events;

use App\Models\GameMatch;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerAssignedToMatch
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $player,
        public GameMatch $match
    ) {
    }
}