<?php

namespace App\Listeners;

use App\Events\MatchCreated;
use Illuminate\Support\Facades\Log;

class LogMatchCreation
{
    public function handle(MatchCreated $event): void
    {
        Log::info('Match created.', [
            'match_name' => $event->match->match_name,
            'match_date' => $event->match->match_date,
            'location' => $event->match->location,
        ]);
    }
}