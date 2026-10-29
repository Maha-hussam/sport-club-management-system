<?php

use App\Models\GameMatch;
use App\Models\User;

test('player can view their dashboard', function () {
    $player = User::factory()->player()->create();

    $this->actingAs($player)
        ->get('/player/dashboard')
        ->assertOk();
});

test('player can view their profile', function () {
    $player = User::factory()->player()->create();

    $this->actingAs($player)
        ->get('/player/dashboard/profile')
        ->assertOk();
});

test('player can view their assigned matches', function () {
    $player = User::factory()->player()->create();

    $match = GameMatch::create([
        'match_name' => 'Test Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => 'Test match description',
    ]);

    $match->players()->attach($player->id);

    $this->actingAs($player)
        ->get('/player/dashboard/matches')
        ->assertOk();
});

test('player cannot view an unassigned match', function () {
    $player = User::factory()->player()->create();

    $match = GameMatch::create([
        'match_name' => 'Private Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => 'Unassigned match',
    ]);

    $this->actingAs($player)
        ->get("/player/dashboard/matches/{$match->id}")
        ->assertForbidden();
});