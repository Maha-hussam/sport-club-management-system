<?php

use App\Models\User;
use App\Models\GameMatch;
use Laravel\Sanctum\Sanctum;

test('unauthenticated users cannot access the API matches', function () {
    $this->getJson('/api/matches')
        ->assertUnauthorized();
});

test('unauthenticated users cannot access the API players', function () {
    $this->getJson('/api/players')
        ->assertUnauthorized();
});

test('trainer can access the API matches', function () {
    $trainer = User::factory()->trainer()->create();

    Sanctum::actingAs($trainer);

    $this->getJson('/api/matches')
        ->assertOk();
});

test('player cannot create a match through the API', function () {
    $player = User::factory()->player()->create();

    Sanctum::actingAs($player);

    $this->postJson('/api/matches', [
        'match_name' => 'API Test Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => 'Test description',
    ])->assertForbidden();
});

test('player cannot update a match through the API', function () {
    $player = User::factory()->player()->create();

    $match = GameMatch::create([
        'match_name' => 'Original Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => 'Original description',
    ]);

    Sanctum::actingAs($player);

    $this->putJson("/api/matches/{$match->id}", [
        'match_name' => 'Updated Match',
    ])->assertForbidden();
});

test('player cannot delete a match through the API', function () {
    $player = User::factory()->player()->create();

    $match = GameMatch::create([
        'match_name' => 'Protected Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => 'Protected description',
    ]);

    Sanctum::actingAs($player);

    $this->deleteJson("/api/matches/{$match->id}")
        ->assertForbidden();
});