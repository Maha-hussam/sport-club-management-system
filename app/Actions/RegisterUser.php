<?php

namespace App\Actions;

use App\Events\PlayerRegistered;
use App\Models\User;

class RegisterUser
{
    public function handle(array $data): User
    {
        $user = User::create($data);

        if ($user->role === 'player') {
            PlayerRegistered::dispatch($user);
        }

        return $user;
    }
}