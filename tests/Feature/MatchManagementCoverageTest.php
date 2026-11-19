<?php

use App\Models\GameMatch;
use App\Models\User;

test('trainer can delete a match', function () {
    $trainer = User::factory()->trainer()->create();
    $match = GameMatch::factory()->create();

    $response = $this->actingAs($trainer)->delete(
        route('trainer.matches.destroy', $match)
    );

    $response->assertRedirect(route('trainer.matches.index'));

    $this->assertDatabaseMissing('matches', [
        'id' => $match->id,
    ]);
});

test('player cannot delete a match', function () {
    $player = User::factory()->player()->create();
    $match = GameMatch::factory()->create();

    $response = $this->actingAs($player)->delete(
        route('trainer.matches.destroy', $match)
    );

    $response->assertForbidden();

    $this->assertDatabaseHas('matches', [
        'id' => $match->id,
    ]);
});

test('trainer can remove all players from a match', function () {
    $trainer = User::factory()->trainer()->create();
    $playerOne = User::factory()->player()->create();
    $playerTwo = User::factory()->player()->create();

    $match = GameMatch::factory()->create();

    $match->players()->attach([
        $playerOne->id,
        $playerTwo->id,
    ]);

    $response = $this->actingAs($trainer)->put(
        route('trainer.matches.players.update', $match),
        ['players' => []]
    );

    $response->assertRedirect();

    expect($match->fresh()->players()->count())->toBe(0);
});


test('trainer can view the create match page', function () {
    $trainer = User::factory()->trainer()->create();

    $response = $this->actingAs($trainer)->get(
        route('trainer.matches.create')
    );

    $response->assertOk();
    $response->assertViewIs('trainer.matches.create');
});

test('trainer can view match details with assigned players', function () {
    $trainer = User::factory()->trainer()->create();

    $playerOne = User::factory()->player()->create([
        'first_name' => 'Ahmed',
    ]);

    $playerTwo = User::factory()->player()->create([
        'first_name' => 'Mohammed',
    ]);

    $match = GameMatch::factory()->create();

    $match->players()->attach([
        $playerOne->id,
        $playerTwo->id,
    ]);

    $response = $this->actingAs($trainer)->get(
        route('trainer.matches.show', $match)
    );

    $response->assertOk();

    $response->assertViewIs('trainer.matches.show');

    $response->assertViewHas('match', function ($viewMatch) use ($match) {
        return $viewMatch->id === $match->id
            && $viewMatch->relationLoaded('players')
            && $viewMatch->players->count() === 2;
    });

    $response->assertViewHas('players', function ($players) use ($playerOne, $playerTwo) {
        return $players->contains('id', $playerOne->id)
            && $players->contains('id', $playerTwo->id);
    });
});

test('trainer can view the edit match page', function () {
    $trainer = User::factory()->trainer()->create();

    $match = GameMatch::factory()->create();

    $response = $this->actingAs($trainer)->get(
        route('trainer.matches.edit', $match)
    );

    $response->assertOk();

    $response->assertViewIs('trainer.matches.edit');

    $response->assertViewHas('match', function ($viewMatch) use ($match) {
        return $viewMatch->id === $match->id;
    });
});