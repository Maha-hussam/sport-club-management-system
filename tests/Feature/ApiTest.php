<?php

use App\Models\User;
use App\Models\GameMatch;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

test('user can login successfully through the API', function () {
    $user = User::factory()->player()->create([
        'email' => 'player@example.com',
        'password' => Hash::make('Password123!'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'Password123!',
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'Login successful.')
        ->assertJsonPath('user.id', $user->id)
        ->assertJsonPath('user.email', 'player@example.com')
        ->assertJsonPath('user.role', 'player')
        ->assertJsonPath('token_type', 'Bearer')
        ->assertJsonStructure([
            'token',
            'user' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'role',
            ],
        ]);

    expect($response->json('token'))->not->toBeEmpty();
});

test('user cannot login with invalid API credentials', function () {
    User::factory()->player()->create([
        'email' => 'player@example.com',
        'password' => Hash::make('Password123!'),
    ]);

    $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'WrongPassword123!',
    ])
        ->assertUnauthorized()
        ->assertJson([
            'message' => 'The provided credentials are incorrect.',
        ]);
});

test('API login requires email and password', function () {
    $this->postJson('/api/login', [
        'email' => '',
        'password' => '',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email',
            'password',
        ]);
});

test('user can logout and API token is revoked', function () {
    $user = User::factory()->player()->create([
        'email' => 'player@example.com',
        'password' => Hash::make('Password123!'),
    ]);

    $loginResponse = $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'Password123!',
    ]);

    $loginResponse->assertOk();

    $token = $loginResponse->json('token');

    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $user->id,
        'tokenable_type' => $user->getMorphClass(),
    ]);

    $this->withToken($token)
        ->postJson('/api/logout')
        ->assertOk()
        ->assertJson([
            'message' => 'Logged out successfully.',
        ]);

    $this->assertDatabaseMissing('personal_access_tokens', [
        'tokenable_id' => $user->id,
        'tokenable_type' => $user->getMorphClass(),
    ]);

    Auth::forgetGuards();

    $this->withToken($token)
        ->getJson('/api/user')
        ->assertUnauthorized();
});

test('API returns 404 when viewing a player that does not exist', function () {
    $trainer = User::factory()->trainer()->create();

    Sanctum::actingAs($trainer);

    $this->getJson('/api/players/999999')
        ->assertNotFound();
});

test('API returns 404 when updating a player that does not exist', function () {
    $trainer = User::factory()->trainer()->create();

    Sanctum::actingAs($trainer);

    $this->putJson('/api/players/999999', [
        'first_name' => 'Updated',
    ])->assertNotFound();
});

test('API returns 404 when deleting a player that does not exist', function () {
    $trainer = User::factory()->trainer()->create();

    Sanctum::actingAs($trainer);

    $this->deleteJson('/api/players/999999')
        ->assertNotFound();
});

test('API returns 404 when viewing a match that does not exist', function () {
    $trainer = User::factory()->trainer()->create();

    Sanctum::actingAs($trainer);

    $this->getJson('/api/matches/999999')
        ->assertNotFound();
});

test('API returns 404 when updating a match that does not exist', function () {
    $trainer = User::factory()->trainer()->create();

    Sanctum::actingAs($trainer);

    $this->putJson('/api/matches/999999', [
        'match_name' => 'Updated Match',
    ])->assertNotFound();
});

test('API returns 404 when deleting a match that does not exist', function () {
    $trainer = User::factory()->trainer()->create();

    Sanctum::actingAs($trainer);

    $this->deleteJson('/api/matches/999999')
        ->assertNotFound();
});

test('API returns 404 when assigning players to a match that does not exist', function () {
    $trainer = User::factory()->trainer()->create();

    Sanctum::actingAs($trainer);

    $this->putJson('/api/matches/999999/players', [
        'player_ids' => [],
    ])->assertNotFound();
});


test('trainer can create a player through the API', function () {
    $trainer = User::factory()->trainer()->create();

    Sanctum::actingAs($trainer);

    $response = $this->postJson('/api/players', [
        'first_name' => 'Ahmad',
        'last_name' => 'Ali',
        'email' => 'ahmad@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'role' => 'trainer',
    ]);

    $response->assertCreated();

    $this->assertDatabaseHas('users', [
        'first_name' => 'Ahmad',
        'last_name' => 'Ali',
        'email' => 'ahmad@example.com',
        'role' => 'player',
    ]);
});

test('API rejects creating a player with invalid data', function () {
    $trainer = User::factory()->trainer()->create();

    Sanctum::actingAs($trainer);

    $this->postJson('/api/players', [
        'first_name' => '',
        'last_name' => '',
        'email' => 'invalid-email',
        'password' => 'Password123!',
        'password_confirmation' => 'WrongPassword123!',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'first_name',
            'last_name',
            'email',
            'password',
        ]);
});

test('API rejects creating a player with an existing email', function () {
    $trainer = User::factory()->trainer()->create();

    $existingPlayer = User::factory()->player()->create([
        'email' => 'existing@example.com',
    ]);

    Sanctum::actingAs($trainer);

    $this->postJson('/api/players', [
        'first_name' => 'Ahmad',
        'last_name' => 'Ali',
        'email' => $existingPlayer->email,
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('API rejects updating a player with invalid data', function () {
    $trainer = User::factory()->trainer()->create();
    $player = User::factory()->player()->create();

    Sanctum::actingAs($trainer);

    $this->putJson("/api/players/{$player->id}", [
        'first_name' => '',
        'email' => 'invalid-email',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'first_name',
            'email',
        ]);
});

test('API rejects updating a player with another player email', function () {
    $trainer = User::factory()->trainer()->create();

    $player = User::factory()->player()->create([
        'email' => 'player1@example.com',
    ]);

    $anotherPlayer = User::factory()->player()->create([
        'email' => 'player2@example.com',
    ]);

    Sanctum::actingAs($trainer);

    $this->putJson("/api/players/{$player->id}", [
        'email' => $anotherPlayer->email,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('API converts player email to lowercase when updating', function () {
    $trainer = User::factory()->trainer()->create();
    $player = User::factory()->player()->create();

    Sanctum::actingAs($trainer);

    $this->putJson("/api/players/{$player->id}", [
        'email' => 'NEWPLAYER@EXAMPLE.COM',
    ])
        ->assertOk();

    $this->assertDatabaseHas('users', [
        'id' => $player->id,
        'email' => 'newplayer@example.com',
    ]);
});


test('trainer can create a match through the API', function () {
    $trainer = User::factory()->trainer()->create();

    Sanctum::actingAs($trainer);

    $response = $this->postJson('/api/matches', [
        'match_name' => 'API Championship',
        'match_date' => '2026-12-10',
        'match_time' => '18:00',
        'location' => 'National Stadium',
        'description' => 'Championship match',
    ]);

    $response->assertCreated();

    $this->assertDatabaseHas('matches', [
        'match_name' => 'API Championship',
        'match_date' => '2026-12-10',
        'match_time' => '18:00',
        'location' => 'National Stadium',
    ]);
});

test('trainer can update a match through the API', function () {
    $trainer = User::factory()->trainer()->create();

    $match = GameMatch::factory()->create([
        'match_name' => 'Original Match',
    ]);

    Sanctum::actingAs($trainer);

    $response = $this->putJson("/api/matches/{$match->id}", [
        'match_name' => 'Updated API Match',
        'match_date' => '2026-12-15',
        'match_time' => '19:00',
        'location' => 'Updated Stadium',
        'description' => 'Updated description',
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('matches', [
        'id' => $match->id,
        'match_name' => 'Updated API Match',
        'match_date' => '2026-12-15',
        'location' => 'Updated Stadium',
    ]);
});

test('trainer can assign players to a match through the API', function () {
    $trainer = User::factory()->trainer()->create();

    $playerOne = User::factory()->player()->create();
    $playerTwo = User::factory()->player()->create();

    $match = GameMatch::factory()->create();

    Sanctum::actingAs($trainer);

    $response = $this->putJson("/api/matches/{$match->id}/players", [
        'player_ids' => [
            $playerOne->id,
            $playerTwo->id,
        ],
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('match_player', [
        'match_id' => $match->id,
        'player_id' => $playerOne->id,
    ]);

    $this->assertDatabaseHas('match_player', [
        'match_id' => $match->id,
        'player_id' => $playerTwo->id,
    ]);
});

test('trainer can delete a match through the API', function () {
    $trainer = User::factory()->trainer()->create();

    $player = User::factory()->player()->create();

    $match = GameMatch::factory()->create();

    $match->players()->attach($player->id);

    Sanctum::actingAs($trainer);

    $response = $this->deleteJson("/api/matches/{$match->id}");

    $response->assertOk()
        ->assertJson([
            'message' => 'Match deleted successfully.',
        ]);

    $this->assertDatabaseMissing('matches', [
        'id' => $match->id,
    ]);

    $this->assertDatabaseMissing('match_player', [
        'match_id' => $match->id,
        'player_id' => $player->id,
    ]);
});