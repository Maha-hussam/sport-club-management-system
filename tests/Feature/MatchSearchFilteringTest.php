<?php

use App\Models\GameMatch;
use App\Models\User;

beforeEach(function () {
    $this->trainer = User::factory()->trainer()->create();
});

it('allows trainers to search matches by partial match name or location', function (string $search) {
    $matchingMatch = GameMatch::factory()->create([
        'match_name' => 'Championship Final',
        'location' => 'Central Stadium',
    ]);

    $otherMatch = GameMatch::factory()->create([
        'match_name' => 'Friendly Game',
        'location' => 'Training Ground',
    ]);

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.matches.index', [
            'search' => $search,
        ]));

    $response->assertOk()
        ->assertViewHas('matches', function ($matches) use ($matchingMatch, $otherMatch) {
            return $matches->contains('id', $matchingMatch->id)
                && !$matches->contains('id', $otherMatch->id);
        });
})->with([
            'match name' => 'Championship',
            'location' => 'Central',
        ]);

it('filters matches by exact match date', function () {
    $matchingMatch = GameMatch::factory()->create([
        'match_date' => '2026-11-10',
    ]);

    $otherMatch = GameMatch::factory()->create([
        'match_date' => '2026-11-11',
    ]);

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.matches.index', [
            'match_date' => '2026-11-10',
        ]));

    $response->assertOk()
        ->assertViewHas('matches', function ($matches) use ($matchingMatch, $otherMatch) {
            return $matches->contains('id', $matchingMatch->id)
                && !$matches->contains('id', $otherMatch->id);
        });
});

it('filters matches by partial location', function () {
    $matchingMatch = GameMatch::factory()->create([
        'location' => 'Central Sports Stadium',
    ]);

    $otherMatch = GameMatch::factory()->create([
        'location' => 'North Training Ground',
    ]);

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.matches.index', [
            'location' => 'Sports',
        ]));

    $response->assertOk()
        ->assertViewHas('matches', function ($matches) use ($matchingMatch, $otherMatch) {
            return $matches->contains('id', $matchingMatch->id)
                && !$matches->contains('id', $otherMatch->id);
        });
});

it('sorts matches by match name in ascending or descending order', function (string $direction, array $expectedOrder) {
    foreach (['Charlie Match', 'Alpha Match', 'Bravo Match'] as $name) {
        GameMatch::factory()->create([
            'match_name' => $name,
        ]);
    }

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.matches.index', [
            'sort_by' => 'match_name',
            'sort_direction' => $direction,
        ]));

    $response->assertOk()
        ->assertViewHas('matches', function ($matches) use ($expectedOrder) {
            return $matches->pluck('match_name')->all() === $expectedOrder;
        });
})->with([
            'ascending' => ['asc', ['Alpha Match', 'Bravo Match', 'Charlie Match']],
            'descending' => ['desc', ['Charlie Match', 'Bravo Match', 'Alpha Match']],
        ]);

it('sorts matches by match date in ascending or descending order', function (string $direction, array $expectedOrder) {
    $dates = [
        'Middle Match' => '2026-11-10',
        'Old Match' => '2026-11-08',
        'New Match' => '2026-11-12',
    ];

    foreach ($dates as $name => $date) {
        GameMatch::factory()->create([
            'match_name' => $name,
            'match_date' => $date,
        ]);
    }

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.matches.index', [
            'sort_by' => 'match_date',
            'sort_direction' => $direction,
        ]));

    $response->assertOk()
        ->assertViewHas('matches', function ($matches) use ($expectedOrder) {
            return $matches->pluck('match_name')->all() === $expectedOrder;
        });
})->with([
            'ascending' => ['asc', ['Old Match', 'Middle Match', 'New Match']],
            'descending' => ['desc', ['New Match', 'Middle Match', 'Old Match']],
        ]);

it('paginates matches and preserves search and sorting options', function () {
    for ($i = 1; $i <= 21; $i++) {
        GameMatch::factory()->create([
            'match_name' => 'Pagination Match ' . $i,
        ]);
    }

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.matches.index', [
            'search' => 'Pagination',
            'sort_by' => 'match_name',
            'sort_direction' => 'asc',
            'page' => 2,
        ]));

    $response->assertOk()
        ->assertViewHas('matches', function ($matches) {
            return $matches->total() === 21
                && $matches->currentPage() === 2
                && $matches->lastPage() === 3
                && $matches->perPage() === 10
                && $matches->count() === 10
                && str_contains($matches->url(3), 'search=Pagination')
                && str_contains($matches->url(3), 'sort_by=match_name')
                && str_contains($matches->url(3), 'sort_direction=asc');
        });
});

it('returns an empty result when no matches match the search', function () {
    GameMatch::factory()->create([
        'match_name' => 'Friendly Match',
    ]);

    $response = $this->actingAs($this->trainer)
        ->get(route('trainer.matches.index', [
            'search' => 'NoMatchingMatch',
        ]));

    $response->assertOk()
        ->assertViewHas('matches', function ($matches) {
            return $matches->isEmpty() && $matches->total() === 0;
        });
});