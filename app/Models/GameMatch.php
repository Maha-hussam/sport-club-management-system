<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class GameMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'match_name',
        'match_date',
        'match_time',
        'location',
        'description',
    ];

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'match_player',
            'match_id',
            'player_id'
        )->where('role', 'player');
    }
}