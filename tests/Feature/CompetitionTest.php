<?php

use App\Models\Competition;
use App\Models\User;

test('empty competitions list is displayed', function () {
    $user = User::factory()->create();

    Competition::query()->delete();

    $response = $this
        ->actingAs($user)
        ->get('/competitions');

    $response->assertOk();
});

test('populated competitions list is displayed', function () {
    $user = User::factory()->create();

    Competition::factory()
        ->count(10)
        ->create();

    $response = $this
        ->actingAs($user)
        ->get('/competitions');

    $response->assertOk();
});

test('competition information is displayed', function () {
    $user = User::factory()->create();

    $competition = Competition::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get("/competitions/$competition->id");

    $response->assertOk();
});

test('new competition form is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/competitions/create');

    $response->assertOk();
});

test('new competition can be added', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/competitions', [
            'name'             => 'Indoor League 2024',
            'entries_open_on'  => '2023-12-01',
            'entries_close_on' => '2024-01-07',
            'stages'           => [
                [
                    'type'      => 'league',
                    'starts_on' => '2024-01-01',
                    'ends_on'   => '2024-03-31',
                ],
                [
                    'type'      => 'playoff',
                    'starts_on' => '2024-04-01',
                    'ends_on'   => '2024-04-01',
                ],
            ],
        ]);

    $response->assertRedirect('/competitions');

    $this->assertDatabaseHas('competitions', [
        'name'             => 'Indoor League 2024',
        'entries_open_on'  => '2023-12-01',
        'entries_close_on' => '2024-01-07',
        'starts_on'        => '2024-01-01',
        'ends_on'          => '2024-04-01',
    ]);

    $this->assertDatabaseHas('stages', [
        'name'      => 'League Stage',
        'type'      => 'league',
        'starts_on' => '2024-01-01',
        'ends_on'   => '2024-03-31',
    ]);

    $this->assertDatabaseHas('stages', [
        'name'      => 'Playoff Stage',
        'type'      => 'playoff',
        'starts_on' => '2024-04-01',
        'ends_on'   => '2024-04-01',
    ]);
});

test('competition editing form is displayed', function () {
    $user        = User::factory()->create();
    $competition = Competition::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get("/competitions/$competition->id/edit");

    $response->assertOk();
});

test('competition can be updated', function () {
    $user = User::factory()->create();

    $competition = Competition::factory()
        ->hasStages(2)
        ->create(['name' => 'Indoor League 2024']);

    $response = $this
        ->actingAs($user)
        ->put("/competitions/$competition->id", [
            'name'             => 'Indoor League 2023/24',
            'entries_open_on'  => '2023-12-01',
            'entries_close_on' => '2024-01-07',
            'stages'           => [
                [
                    'id'        => $competition->stages[0]->id,
                    'type'      => 'league',
                    'starts_on' => '2024-01-01',
                    'ends_on'   => '2024-03-31',
                ],
                [
                    'type'      => 'playoff',
                    'starts_on' => '2024-04-01',
                    'ends_on'   => '2024-04-01',
                ],
            ],
        ]);

    $response->assertRedirect('/competitions');

    $this->assertDatabaseHas('competitions', [
        'name' => 'Indoor League 2023/24',
    ]);
});

test('competition can be removed', function () {
    $user = User::factory()->create();

    $competition = Competition::factory()
        ->hasStages(2)
        ->create();

    $response = $this
        ->actingAs($user)
        ->delete("/competitions/$competition->id");

    $response->assertRedirect('/competitions');

    $this->assertSoftDeleted('competitions', [
        'id' => $competition->id,
    ]);

    $this->assertSoftDeleted('stages', [
        'competition_id' => $competition->id,
    ]);
});
