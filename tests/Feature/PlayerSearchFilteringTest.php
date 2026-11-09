<?php

use App\Models\GameMatch;
use App\Models\User;

beforeEach(function () {
    $this->trainer = User::factory()->trainer()->create();
});

it('allows trainers to search players by partial first name, last name, or email', function (string $search) {
    $player = User::factory()->player()->create([
        'first_name' => 'Alexandra',
        'last_name' => 'Stone',
        'email' => 'alexandra.stone@example.test',
    ]);

    $otherPlayer = User::factory()->player()->create([
        'first_name' => 'Michael',
        'last_name' => 'Brown',
        'email' => 'michael.brown@example.test',
    ]);

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.players.index', ['search' => $search]));

    $response->assertOk()
        ->assertViewHas('players', function ($players) use ($player, $otherPlayer) {
            return $players->contains('id', $player->id)
                && !$players->contains('id', $otherPlayer->id);
        });
})->with([
            'first name' => 'Alex',
            'last name' => 'Stone',
            'email' => 'alexandra.stone',
        ]);

it('filters players by registration date range', function () {
    $matchingPlayer = User::factory()->player()->create();
    $matchingPlayer->forceFill([
        'created_at' => '2026-10-15 10:00:00',
    ])->save();

    $olderPlayer = User::factory()->player()->create();
    $olderPlayer->forceFill([
        'created_at' => '2026-09-15 10:00:00',
    ])->save();

    $newerPlayer = User::factory()->player()->create();
    $newerPlayer->forceFill([
        'created_at' => '2026-11-15 10:00:00',
    ])->save();

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.players.index', [
            'registered_from' => '2026-10-01',
            'registered_to' => '2026-10-31',
        ]));

    $response->assertOk()
        ->assertViewHas('players', function ($players) use ($matchingPlayer, $olderPlayer, $newerPlayer) {
            return $players->contains('id', $matchingPlayer->id)
                && !$players->contains('id', $olderPlayer->id)
                && !$players->contains('id', $newerPlayer->id);
        });
});

it('filters players by the exact number of assigned matches', function () {
    $playerWithTwoMatches = User::factory()->player()->create();
    $playerWithOneMatch = User::factory()->player()->create();

    $matches = GameMatch::factory()->count(2)->create();

    $playerWithTwoMatches->matches()->attach($matches->pluck('id'));

    $playerWithOneMatch->matches()->attach($matches->first()->id);

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.players.index', [
            'matches_count' => 2,
        ]));

    $response->assertOk()
        ->assertViewHas('players', function ($players) use ($playerWithTwoMatches, $playerWithOneMatch) {
            return $players->contains('id', $playerWithTwoMatches->id)
                && !$players->contains('id', $playerWithOneMatch->id);
        });
});

it('sorts players by first name in ascending or descending order', function (string $direction, array $expectedOrder) {
    $players = collect(['Charlie', 'Alice', 'Bob'])
        ->map(fn($name) => User::factory()->player()->create([
            'first_name' => $name,
        ]));

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.players.index', [
            'sort_by' => 'first_name',
            'sort_direction' => $direction,
        ]));

    $response->assertOk()
        ->assertViewHas('players', function ($paginator) use ($expectedOrder) {
            return $paginator->pluck('first_name')->all() === $expectedOrder;
        });
})->with([
            'ascending' => ['asc', ['Alice', 'Bob', 'Charlie']],
            'descending' => ['desc', ['Charlie', 'Bob', 'Alice']],
        ]);

it('paginates players and preserves search filters in pagination links', function () {
    User::factory()->player()->create([
        'first_name' => 'Searchable',
    ]);

    User::factory()->player()->count(10)->create();

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.players.index', [
            'search' => 'Searchable',
        ]));

    $response->assertOk()
        ->assertViewHas('players', function ($players) {
            return $players->total() === 1
                && $players->currentPage() === 1
                && $players->lastPage() === 1
                && $players->perPage() === 10;
        });

    $response->assertViewHas('players', function ($players) {
        return str_contains($players->url(2), 'search=Searchable');
    });
});

it('returns an empty result when no players match the search', function () {
    User::factory()->player()->create([
        'first_name' => 'John',
    ]);

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.players.index', [
            'search' => 'NoMatchingPlayer',
        ]));

    $response->assertOk()
        ->assertViewHas('players', function ($players) {
            return $players->isEmpty() && $players->total() === 0;
        });
});

it('does not include trainers in player search results', function () {
    $player = User::factory()->player()->create([
        'first_name' => 'Alex',
    ]);

    User::factory()->trainer()->create([
        'first_name' => 'Alex',
    ]);

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.players.index', [
            'search' => 'Alex',
        ]));

    $response->assertOk()
        ->assertViewHas('players', function ($players) use ($player) {
            return $players->count() === 1
                && $players->first()->id === $player->id;
        });
});

it('sorts players by registration date in ascending or descending order', function (string $direction, array $expectedOrder) {
    $dates = [
        'Charlie' => '2026-10-15 10:00:00',
        'Alice' => '2026-09-15 10:00:00',
        'Bob' => '2026-11-15 10:00:00',
    ];

    foreach ($dates as $name => $date) {
        $player = User::factory()->player()->create([
            'first_name' => $name,
        ]);

        $player->forceFill([
            'created_at' => $date,
        ])->save();
    }

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.players.index', [
            'sort_by' => 'created_at',
            'sort_direction' => $direction,
        ]));

    $response->assertOk()
        ->assertViewHas('players', function ($players) use ($expectedOrder) {
            return $players->pluck('first_name')->all() === $expectedOrder;
        });
})->with([
            'ascending' => ['asc', ['Alice', 'Charlie', 'Bob']],
            'descending' => ['desc', ['Bob', 'Charlie', 'Alice']],
        ]);

it('paginates more than ten players and preserves all query options', function () {
    for ($i = 1; $i <= 21; $i++) {
        User::factory()->player()->create([
            'first_name' => 'Pagination Player ' . $i,
        ]);
    }

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.players.index', [
            'search' => 'Pagination',
            'sort_by' => 'first_name',
            'sort_direction' => 'asc',
            'page' => 2,
        ]));

    $response->assertOk()
        ->assertViewHas('players', function ($players) {
            return $players->total() === 21
                && $players->currentPage() === 2
                && $players->lastPage() === 3
                && $players->perPage() === 10
                && $players->count() === 10
                && str_contains($players->url(3), 'search=Pagination')
                && str_contains($players->url(3), 'sort_by=first_name')
                && str_contains($players->url(3), 'sort_direction=asc');
        });
});