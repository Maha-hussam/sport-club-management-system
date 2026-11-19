<?php

use App\Models\User;

test('user cannot register with an already registered email', function () {
    User::factory()->create([
        'email' => 'existing@example.com',
    ]);

    $response = $this->post('/register', [
        'first_name' => 'Ahmad',
        'last_name' => 'Ali',
        'email' => 'existing@example.com',
        'role' => 'player',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertSessionHasErrors('email');

    $this->assertDatabaseCount('users', 1);
});

test('user cannot register with missing required information', function () {
    $response = $this->post('/register', []);

    $response->assertSessionHasErrors();

    $this->assertDatabaseCount('users', 0);
});