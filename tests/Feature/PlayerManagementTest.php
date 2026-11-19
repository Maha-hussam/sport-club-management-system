<?php

use App\Models\User;

test('trainer can create a new player', function () {
    $trainer = User::factory()->trainer()->create();

    $response = $this->actingAs($trainer)->post(
        route('trainer.players.store'),
        [
            'first_name' => 'Ahmad',
            'last_name' => 'Ali',
            'email' => 'ahmad@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]
    );

    $response->assertRedirect(route('trainer.players.index'));

    $this->assertDatabaseHas('users', [
        'first_name' => 'Ahmad',
        'last_name' => 'Ali',
        'email' => 'ahmad@example.com',
        'role' => 'player',
    ]);
});

test('trainer can update a player', function () {
    $trainer = User::factory()->trainer()->create();

    $player = User::factory()->player()->create([
        'first_name' => 'Ahmad',
        'last_name' => 'Ali',
    ]);

    $response = $this->actingAs($trainer)->put(
        route('trainer.players.update', $player),
        [
            'first_name' => 'Mohammad',
            'last_name' => 'Hassan',
            'email' => $player->email,
        ]
    );

    $response->assertRedirect(route('trainer.players.index'));

    $this->assertDatabaseHas('users', [
        'id' => $player->id,
        'first_name' => 'Mohammad',
        'last_name' => 'Hassan',
    ]);
});

test('trainer can delete a player', function () {
    $trainer = User::factory()->trainer()->create();

    $player = User::factory()->player()->create();

    $response = $this->actingAs($trainer)->delete(
        route('trainer.players.destroy', $player)
    );

    $response->assertRedirect(route('trainer.players.index'));

    $this->assertDatabaseMissing('users', [
        'id' => $player->id,
    ]);
});

test('trainer cannot create a player with invalid information', function () {
    $trainer = User::factory()->trainer()->create();

    $response = $this->actingAs($trainer)->post(
        route('trainer.players.store'),
        [
            'first_name' => '',
            'last_name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456',
        ]
    );

    $response->assertSessionHasErrors([
        'first_name',
        'last_name',
        'email',
        'password',
    ]);

    $this->assertDatabaseCount('users', 1);
});

test('trainer cannot create a player with an existing email', function () {
    $trainer = User::factory()->trainer()->create();

    $existingPlayer = User::factory()->player()->create([
        'email' => 'existing@example.com',
    ]);

    $response = $this->actingAs($trainer)->post(
        route('trainer.players.store'),
        [
            'first_name' => 'New',
            'last_name' => 'Player',
            'email' => $existingPlayer->email,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]
    );

    $response->assertSessionHasErrors('email');

    $this->assertDatabaseCount('users', 2);
});

test('player cannot create another player', function () {
    $player = User::factory()->player()->create();

    $response = $this->actingAs($player)->post(
        route('trainer.players.store'),
        [
            'first_name' => 'New',
            'last_name' => 'Player',
            'email' => 'newplayer@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]
    );

    $response->assertForbidden();

    $this->assertDatabaseMissing('users', [
        'email' => 'newplayer@example.com',
    ]);
});