<?php

use App\Models\User;

it('allows a trainer to access the trainer dashboard', function () {
    $trainer = User::factory()->trainer()->create();

    $response = $this->actingAs($trainer)
        ->get(route('trainer.dashboard'));

    $response->assertOk();
    $response->assertViewIs('trainer.dashboard');
});

it('prevents a player from accessing the trainer dashboard', function () {
    $player = User::factory()->player()->create();

    $response = $this->actingAs($player)
        ->get(route('trainer.dashboard'));

    $response->assertForbidden();
});

it('redirects guests to login when accessing the trainer dashboard', function () {
    $response = $this->get(route('trainer.dashboard'));

    $response->assertRedirect(route('login'));
});