<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function scopeSearch(
        Builder $query,
        ?string $search
    ): Builder {
        if ($search === null || $search === '') {
            return $query;
        }

        $search = self::escapeLike($search);
        $pattern = "%{$search}%";

        return $query->where(function (Builder $q) use ($pattern) {
            $q->whereRaw("match_name LIKE ? ESCAPE '!'", [$pattern])
                ->orWhereRaw("location LIKE ? ESCAPE '!'", [$pattern]);
        });
    }

    public function scopeOnDate(
        Builder $query,
        ?string $date
    ): Builder {
        if ($date === null) {
            return $query;
        }

        return $query->whereDate('match_date', $date);
    }

    public function scopeAtLocation(
        Builder $query,
        ?string $location
    ): Builder {
        if ($location === null || $location === '') {
            return $query;
        }

        $location = self::escapeLike($location);
        $pattern = "%{$location}%";

        return $query->whereRaw(
            "location LIKE ? ESCAPE '!'",
            [$pattern]
        );
    }

    private static function escapeLike(string $value): string
    {
        return str_replace(
            ['!', '%', '_'],
            ['!!', '!%', '!_'],
            $value
        );
    }

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