<?php

use App\Models\Competition;
use App\Models\Entry;
use App\Models\Person;
use App\Models\User;

test('new entry form is displayed', function () {
    $user        = User::factory()->create();
    $competition = Competition::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get("/competitions/$competition->id/entries/create");

    $response->assertOk();
});

test('new entry can be added', function () {
    $user = User::factory()->create();

    $person      = Person::factory()->create();
    $competition = Competition::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post("/competitions/$competition->id/entries", [
            'person_id'        => $person->id,
            'bow_style'        => 'recurve',
            'initial_handicap' => 45,
        ]);

    $response->assertRedirect("/competitions/$competition->id");

    $this->assertDatabaseHas('entries', [
        'competition_id'   => $competition->id,
        'person_id'        => $person->id,
        'bow_style'        => 'recurve',
        'initial_handicap' => 45,
        'current_handicap' => 45,
    ]);
});

test('entry editing form is displayed', function () {
    $user = User::factory()->create();

    $entry = Entry::factory()
        ->forPerson()
        ->forCompetition()
        ->create();

    $response = $this
        ->actingAs($user)
        ->get("/competitions/$entry->competition_id/entries/$entry->id/edit");

    $response->assertOk();
});

test('entry can be updated', function () {
    $user = User::factory()->create();

    $entry = Entry::factory()
        ->forPerson()
        ->forCompetition()
        ->create();

    $response = $this
        ->actingAs($user)
        ->put("/competitions/$entry->competition_id/entries/$entry->id", [
            'competition_id'   => $entry->competition_id,
            'person_id'        => $entry->person_id,
            'bow_style'        => 'compound',
            'initial_handicap' => 45,
            'current_handicap' => 45,
        ]);

    $response->assertRedirect("/competitions/$entry->competition_id");

    $this->assertDatabaseHas('entries', [
        'competition_id'   => $entry->competition_id,
        'person_id'        => $entry->person_id,
        'bow_style'        => 'compound',
        'initial_handicap' => 45,
        'current_handicap' => 45,
    ]);
});

test('entry can be removed', function () {
    $user = User::factory()->create();

    $entry = Entry::factory()
        ->forPerson()
        ->forCompetition()
        ->create();

    $response = $this
        ->actingAs($user)
        ->delete("/competitions/$entry->competition_id/entries/$entry->id");

    $response->assertRedirect("/competitions/$entry->competition_id");

    $this->assertSoftDeleted('entries', [
        'id' => $entry->id,
    ]);
});
