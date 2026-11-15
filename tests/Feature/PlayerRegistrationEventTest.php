<?php

use App\Events\PlayerRegistered;
use App\Listeners\LogPlayerRegistration;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

test('player registration dispatches PlayerRegistered event', function () {
    Event::fake([PlayerRegistered::class]);

    $response = $this->post('/register', [
        'first_name' => 'Test',
        'last_name' => 'Player',
        'email' => 'event-player@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'player',
    ]);

    $response->assertRedirect(route('player.dashboard'));

    Event::assertDispatched(
        PlayerRegistered::class,
        function (PlayerRegistered $event) {
            return $event->player->email === 'event-player@example.com'
                && $event->player->role === 'player';
        }
    );
});

test('trainer registration does not dispatch PlayerRegistered event', function () {
    Event::fake([PlayerRegistered::class]);

    $response = $this->post('/register', [
        'first_name' => 'Test',
        'last_name' => 'Trainer',
        'email' => 'event-trainer@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'trainer',
    ]);

    $response->assertRedirect(route('trainer.dashboard'));

    Event::assertNotDispatched(PlayerRegistered::class);
});

test('listener logs player registration details', function () {
    $player = User::factory()->player()->create([
        'first_name' => 'Ahmad',
        'last_name' => 'Ali',
        'email' => 'ahmad@example.com',
    ]);

    Log::spy();

    $listener = new LogPlayerRegistration();

    $listener->handle(new PlayerRegistered($player));

    Log::shouldHaveReceived('info')
        ->once()
        ->with('New player registered.', [
            'player_id' => $player->id,
            'player_name' => 'Ahmad Ali',
            'player_email' => 'ahmad@example.com',
        ]);
});