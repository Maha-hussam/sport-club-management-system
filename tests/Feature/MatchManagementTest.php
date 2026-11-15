<?php

use App\Models\GameMatch;
use App\Models\User;
use App\Events\PlayerAssignedToMatch;
use App\Listeners\LogPlayerMatchAssignment;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use App\Notifications\PlayerAssignedToMatchNotification;
use Illuminate\Support\Facades\Notification;
use App\Events\MatchCreated;
use App\Listeners\LogMatchCreation;

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

test('trainer assignment dispatches player assigned event', function () {
    Event::fake([PlayerAssignedToMatch::class]);

    $trainer = User::factory()->trainer()->create();
    $player = User::factory()->player()->create();

    $match = GameMatch::create([
        'match_name' => 'Friendly Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => null,
    ]);

    $this->actingAs($trainer)->put(
        route('trainer.matches.players.update', $match),
        ['players' => [$player->id]]
    );

    Event::assertDispatched(
        PlayerAssignedToMatch::class,
        function ($event) use ($player, $match) {
            return $event->player->id === $player->id
                && $event->match->id === $match->id;
        }
    );
});

test('player assignment listener logs player and match details', function () {
    Log::shouldReceive('info')
        ->once()
        ->with('Player assigned to match.', [
            'player_name' => 'John Doe',
            'player_email' => 'john@example.com',
            'match_name' => 'Friendly Match',
        ]);

    $player = User::factory()->player()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
    ]);

    $match = new GameMatch([
        'match_name' => 'Friendly Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
    ]);

    $event = new PlayerAssignedToMatch($player, $match);

    (new LogPlayerMatchAssignment())->handle($event);
});

test('assignment does not dispatch event for an already assigned player', function () {
    Event::fake([PlayerAssignedToMatch::class]);

    $trainer = User::factory()->trainer()->create();
    $player = User::factory()->player()->create();

    $match = GameMatch::create([
        'match_name' => 'Friendly Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => null,
    ]);

    $this->actingAs($trainer)->put(
        route('trainer.matches.players.update', $match),
        ['players' => [$player->id]]
    );

    Event::assertDispatchedTimes(PlayerAssignedToMatch::class, 1);

    $this->put(
        route('trainer.matches.players.update', $match),
        ['players' => [$player->id]]
    );

    Event::assertDispatchedTimes(PlayerAssignedToMatch::class, 1);
});


test('player receives a notification when assigned to a match', function () {
    Notification::fake();

    $trainer = User::factory()->trainer()->create();
    $player = User::factory()->player()->create();

    $match = GameMatch::create([
        'match_name' => 'Friendly Match',
        'match_date' => '2026-11-10',
        'match_time' => '15:30',
        'location' => 'Main Stadium',
        'description' => null,
    ]);

    $this->actingAs($trainer)->put(
        route('trainer.matches.players.update', $match),
        ['players' => [$player->id]]
    );

    Notification::assertSentTo(
        $player,
        PlayerAssignedToMatchNotification::class,
        function ($notification, $channels) use ($match, $player) {
            $data = $notification->toArray($player);

            return in_array('database', $channels)
                && $data['match_name'] === $match->match_name
                && $data['match_date'] === $match->match_date
                && $data['location'] === $match->location;
        }
    );
});


test('creating a match dispatches match created event', function () {
    Event::fake([MatchCreated::class]);

    $trainer = User::factory()->trainer()->create();

    $this->actingAs($trainer)->post(
        route('trainer.matches.store'),
        [
            'match_name' => 'Championship Final',
            'match_date' => '2026-11-20',
            'match_time' => '18:00',
            'location' => 'National Stadium',
            'description' => 'Final match',
        ]
    );

    Event::assertDispatched(
        MatchCreated::class,
        function ($event) {
            return $event->match->match_name === 'Championship Final'
                && $event->match->match_date === '2026-11-20'
                && $event->match->location === 'National Stadium';
        }
    );
});


test('match created listener logs match details', function () {
    Log::shouldReceive('info')
        ->once()
        ->with('Match created.', [
            'match_name' => 'Championship Final',
            'match_date' => '2026-11-20',
            'location' => 'National Stadium',
        ]);

    $trainer = User::factory()->trainer()->create();

    $this->actingAs($trainer)->post(
        route('trainer.matches.store'),
        [
            'match_name' => 'Championship Final',
            'match_date' => '2026-11-20',
            'match_time' => '18:00',
            'location' => 'National Stadium',
            'description' => 'Final match',
        ]
    );
});