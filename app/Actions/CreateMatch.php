<?php

namespace App\Actions;

use App\Events\MatchCreated;
use App\Models\GameMatch;
use Illuminate\Support\Facades\DB;

class CreateMatch
{
    public function handle(array $data): GameMatch
    {
        $match = DB::transaction(function () use ($data) {
            return GameMatch::create($data);
        });

        MatchCreated::dispatch($match);

        return $match;
    }
}