<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
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