<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertOk();
});

test('new players can register', function () {
    $response = $this->post('/register', [
        'first_name' => 'Test',
        'last_name' => 'Player',
        'email' => 'test-player@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'player',
    ]);

    $response->assertRedirect(route('player.dashboard'));

    $this->assertAuthenticated();

    $this->assertDatabaseHas('users', [
        'first_name' => 'Test',
        'last_name' => 'Player',
        'email' => 'test-player@example.com',
        'role' => 'player',
    ]);
});

test('registration rejects an invalid role', function () {
    $response = $this->from('/register')->post('/register', [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'invalid-role@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'admin',
    ]);

    $response->assertSessionHasErrors('role');

    $this->assertGuest();

    $this->assertDatabaseMissing('users', [
        'email' => 'invalid-role@example.com',
    ]);
});


test('registration converts email to lowercase', function () {
    $this->post('/register', [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'Test.User@Example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'player',
    ])->assertRedirect(route('player.dashboard'));

    $this->assertDatabaseHas('users', [
        'email' => 'test.user@example.com',
        'role' => 'player',
    ]);
});