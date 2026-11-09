<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable([
    'first_name',
    'last_name',
    'email',
    'password',
    'role',
    'profile_image',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

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
            $q->whereRaw("first_name LIKE ? ESCAPE '!'", [$pattern])
                ->orWhereRaw("last_name LIKE ? ESCAPE '!'", [$pattern])
                ->orWhereRaw("email LIKE ? ESCAPE '!'", [$pattern]);
        });
    }

    public function scopeRegisteredBetween(
        Builder $query,
        ?string $from,
        ?string $to
    ): Builder {
        if ($from !== null) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to !== null) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query;
    }

    public function scopeHasMatchesCount(
        Builder $query,
        ?int $count
    ): Builder {
        if ($count === null) {
            return $query;
        }

        return $query->has('matches', '=', $count);
    }

    private static function escapeLike(string $value): string
    {
        return str_replace(
            ['!', '%', '_'],
            ['!!', '!%', '!_'],
            $value
        );
    }

    public function matches(): BelongsToMany
    {
        return $this->belongsToMany(
            GameMatch::class,
            'match_player',
            'player_id',
            'match_id'
        );
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            if ($user->role === 'player' && $user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
        });
    }
}