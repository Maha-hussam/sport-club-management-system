<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'match_name' => $this->match_name,
            'match_date' => $this->match_date,
            'match_time' => $this->match_time,
            'location' => $this->location,
            'description' => $this->description,
            'players' => PlayerResource::collection(
                $this->whenLoaded('players')
            ),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}