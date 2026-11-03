<?php

use App\Models\User;

test('players cannot access trainer player management pages', function () {
    $player = User::factory()->player()->create();

    $this->actingAs($player)
        ->get('/trainer/dashboard/players')
        ->assertForbidden();

    $this->actingAs($player)
        ->get('/trainer/dashboard/players/create')
        ->assertForbidden();
});

test('trainer cannot edit another trainer through player routes', function () {
    $trainer = User::factory()->trainer()->create();

    $anotherTrainer = User::factory()->trainer()->create();

    $this->actingAs($trainer)
        ->put("/trainer/dashboard/players/{$anotherTrainer->id}", [
            'first_name' => 'Changed',
        ])
        ->assertNotFound();

    expect($anotherTrainer->fresh()->first_name)
        ->toBe($anotherTrainer->first_name);
});

test('trainer cannot delete another trainer through player routes', function () {
    $trainer = User::factory()->trainer()->create();

    $anotherTrainer = User::factory()->trainer()->create();

    $this->actingAs($trainer)
        ->delete("/trainer/dashboard/players/{$anotherTrainer->id}")
        ->assertNotFound();

    $this->assertDatabaseHas('users', [
        'id' => $anotherTrainer->id,
        'role' => 'trainer',
    ]);
});

test('trainer can update a player without changing the password', function () {
    $trainer = User::factory()->trainer()->create();

    $player = User::factory()->player()->create([
        'first_name' => 'Old',
        'last_name' => 'Player',
        'email' => 'old.player@example.com',
        'password' => 'OldPassword123!',
    ]);

    $originalPassword = $player->password;

    $this->actingAs($trainer)
        ->put("/trainer/dashboard/players/{$player->id}", [
            'first_name' => 'Updated',
            'last_name' => 'Player',
            'email' => 'updated.player@example.com',
            'password' => '',
            'password_confirmation' => '',
        ])
        ->assertRedirect(route('trainer.players.index'))
        ->assertSessionHas('success', 'Player updated successfully.');

    $player->refresh();

    expect($player->first_name)->toBe('Updated')
        ->and($player->email)->toBe('updated.player@example.com')
        ->and($player->password)->toBe($originalPassword);
});