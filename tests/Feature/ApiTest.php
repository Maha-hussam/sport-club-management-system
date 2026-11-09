<?php

use App\Models\User;
use App\Models\GameMatch;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;

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


test('player cannot view all players through the API', function () {
    $player = User::factory()->player()->create();

    Sanctum::actingAs($player);

    $this->getJson('/api/players')
        ->assertForbidden();
});

test('player cannot view another player details through the API', function () {
    $player = User::factory()->player()->create();

    $anotherPlayer = User::factory()->player()->create();

    Sanctum::actingAs($player);

    $this->getJson("/api/players/{$anotherPlayer->id}")
        ->assertForbidden();
});

test('player cannot view all matches through the API', function () {
    $player = User::factory()->player()->create();

    Sanctum::actingAs($player);

    $this->getJson('/api/matches')
        ->assertForbidden();
});

test('player cannot view match details through the API', function () {
    $player = User::factory()->player()->create();

    $match = GameMatch::create([
        'match_name' => 'Protected Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => 'Protected description',
    ]);

    Sanctum::actingAs($player);

    $this->getJson("/api/matches/{$match->id}")
        ->assertForbidden();
});

test('trainer can view all players through the API', function () {
    $trainer = User::factory()->trainer()->create();

    User::factory()->player()->create();

    Sanctum::actingAs($trainer);

    $this->getJson('/api/players')
        ->assertOk();
});

test('trainer can view player details through the API', function () {
    $trainer = User::factory()->trainer()->create();

    $player = User::factory()->player()->create();

    Sanctum::actingAs($trainer);

    $this->getJson("/api/players/{$player->id}")
        ->assertOk();
});

test('trainer can view match details through the API', function () {
    $trainer = User::factory()->trainer()->create();

    $match = GameMatch::create([
        'match_name' => 'Trainer Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => 'Trainer match description',
    ]);

    Sanctum::actingAs($trainer);

    $this->getJson("/api/matches/{$match->id}")
        ->assertOk();
});

test('API login is rate limited after five attempts', function () {
    $credentials = [
        'email' => 'nonexistent@example.com',
        'password' => 'WrongPassword123!',
    ];

    for ($i = 0; $i < 5; $i++) {
        $this->postJson('/api/login', $credentials)
            ->assertUnauthorized();
    }

    $this->postJson('/api/login', $credentials)
        ->assertStatus(429);
});

test('trainer can update a player using the same email through the API', function () {
    $trainer = User::factory()->trainer()->create();

    $player = User::factory()->player()->create();

    $originalEmail = $player->email;

    Sanctum::actingAs($trainer);

    $this->putJson("/api/players/{$player->id}", [
        'first_name' => 'Updated',
        'email' => $originalEmail,
    ])
        ->assertOk()
        ->assertJsonPath('data.email', $originalEmail);

    expect($player->fresh()->first_name)->toBe('Updated')
        ->and($player->fresh()->email)->toBe($originalEmail);
});

test('trainer can remove all players from a match', function () {
    $trainer = User::factory()->trainer()->create();
    $player = User::factory()->player()->create();

    $match = GameMatch::create([
        'match_name' => 'Friendly Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => null,
    ]);

    $match->players()->attach($player->id);

    $response = $this->actingAs($trainer)->put(
        route('trainer.matches.players.update', $match),
        []
    );

    $response->assertRedirect(route('trainer.matches.show', $match));

    $this->assertDatabaseMissing('match_player', [
        'match_id' => $match->id,
        'player_id' => $player->id,
    ]);
});

test('API rejects players field when assigning match players', function () {
    $trainer = User::factory()->trainer()->create();
    $player = User::factory()->player()->create();

    $match = GameMatch::create([
        'match_name' => 'API Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => null,
    ]);

    $match->players()->attach($player->id);

    Sanctum::actingAs($trainer);

    $this->putJson("/api/matches/{$match->id}/players", [
        'players' => [],
    ])->assertUnprocessable();

    $this->assertDatabaseHas('match_player', [
        'match_id' => $match->id,
        'player_id' => $player->id,
    ]);
});

test('API rejects empty request when assigning match players', function () {
    $trainer = User::factory()->trainer()->create();

    $match = GameMatch::create([
        'match_name' => 'API Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => null,
    ]);

    Sanctum::actingAs($trainer);

    $this->putJson("/api/matches/{$match->id}/players", [])
        ->assertUnprocessable();
});

test('API keeps the existing password when password is null', function () {
    $trainer = User::factory()->trainer()->create();
    $player = User::factory()->player()->create();

    $originalPassword = $player->password;

    Sanctum::actingAs($trainer);

    $this->putJson("/api/players/{$player->id}", [
        'password' => null,
    ])->assertOk();

    expect($player->fresh()->password)->toBe($originalPassword);
});

test('API keeps the existing password when password is empty', function () {
    $trainer = User::factory()->trainer()->create();
    $player = User::factory()->player()->create();

    $originalPassword = $player->password;

    Sanctum::actingAs($trainer);

    $this->putJson("/api/players/{$player->id}", [
        'password' => '',
    ])->assertOk();

    expect($player->fresh()->password)->toBe($originalPassword);
});

test('API updates password when a new password is provided', function () {
    $trainer = User::factory()->trainer()->create();
    $player = User::factory()->player()->create();

    Sanctum::actingAs($trainer);

    $this->putJson("/api/players/{$player->id}", [
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ])->assertOk();

    expect(
        Hash::check('NewPassword123!', $player->fresh()->password)
    )->toBeTrue();
});