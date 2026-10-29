<?php

use App\Models\GameMatch;
use App\Models\User;

test('trainer can create a match', function () {
    $trainer = User::factory()->trainer()->create();

    $response = $this->actingAs($trainer)->post(
        route('trainer.matches.store'),
        [
            'match_name' => 'Friendly Match',
            'match_date' => '2026-11-10',
            'match_time' => '15:30',
            'location' => 'Main Stadium',
            'description' => 'A friendly match',
        ]
    );

    $response->assertRedirect(route('trainer.matches.index'));

    $this->assertDatabaseHas('matches', [
        'match_name' => 'Friendly Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
    ]);
});

test('player cannot create a match', function () {
    $player = User::factory()->player()->create();

    $response = $this->actingAs($player)->post(
        route('trainer.matches.store'),
        [
            'match_name' => 'Unauthorized Match',
            'match_date' => '2026-11-10',
            'match_time' => '15:30',
            'location' => 'Main Stadium',
        ]
    );

    $response->assertForbidden();

    $this->assertDatabaseMissing('matches', [
        'match_name' => 'Unauthorized Match',
    ]);
});

test('trainer cannot create a match with an invalid time', function () {
    $trainer = User::factory()->trainer()->create();

    $response = $this->actingAs($trainer)->from('/trainer/dashboard/matches/create')->post(
        route('trainer.matches.store'),
        [
            'match_name' => 'Invalid Match',
            'match_date' => '2026-11-10',
            'match_time' => '25:90',
            'location' => 'Main Stadium',
        ]
    );

    $response->assertSessionHasErrors('match_time');

    $this->assertDatabaseMissing('matches', [
        'match_name' => 'Invalid Match',
    ]);
});

test('trainer can update a match', function () {
    $trainer = User::factory()->trainer()->create();

    $match = GameMatch::create([
        'match_name' => 'Old Match Name',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Old Stadium',
        'description' => 'Old description',
    ]);

    $response = $this->actingAs($trainer)->put(
        route('trainer.matches.update', $match),
        [
            'match_name' => 'Updated Match Name',
            'match_date' => '2026-11-11',
            'match_time' => '16:30',
            'location' => 'New Stadium',
            'description' => 'Updated description',
        ]
    );

    $response->assertRedirect(route('trainer.matches.index'));

    $this->assertDatabaseHas('matches', [
        'id' => $match->id,
        'match_name' => 'Updated Match Name',
        'location' => 'New Stadium',
    ]);
});

test('player cannot update a match', function () {
    $player = User::factory()->player()->create();

    $match = GameMatch::create([
        'match_name' => 'Original Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => null,
    ]);

    $response = $this->actingAs($player)->put(
        route('trainer.matches.update', $match),
        [
            'match_name' => 'Changed Match',
            'match_date' => '2026-11-10',
            'match_time' => '15:30',
            'location' => 'Main Stadium',
        ]
    );

    $response->assertForbidden();

    expect($match->fresh()->match_name)->toBe('Original Match');
});


test('trainer can assign players to a match', function () {
    $trainer = User::factory()->trainer()->create();
    $player = User::factory()->player()->create();

    $match = GameMatch::create([
        'match_name' => 'Friendly Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => null,
    ]);

    $response = $this->actingAs($trainer)->put(
        route('trainer.matches.players.update', $match),
        ['players' => [$player->id]]
    );

    $response->assertRedirect(route('trainer.matches.show', $match));

    $this->assertDatabaseHas('match_player', [
        'match_id' => $match->id,
        'player_id' => $player->id,
    ]);
});

test('trainer cannot assign another trainer to a match', function () {
    $trainer = User::factory()->trainer()->create();
    $anotherTrainer = User::factory()->trainer()->create();

    $match = GameMatch::create([
        'match_name' => 'Friendly Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => null,
    ]);

    $response = $this->actingAs($trainer)->put(
        route('trainer.matches.players.update', $match),
        ['players' => [$anotherTrainer->id]]
    );

    $response->assertSessionHasErrors('players.0');

    $this->assertDatabaseMissing('match_player', [
        'match_id' => $match->id,
        'player_id' => $anotherTrainer->id,
    ]);
});