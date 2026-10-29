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