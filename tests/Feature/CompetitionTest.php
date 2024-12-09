<?php

use App\Models\Competition;
use App\Models\User;

test('empty competitions list is displayed', function () {
    $user = User::factory()->create();

    Competition::truncate();

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

test('competition editing form is displayed', function () {
    $user   = User::factory()->create();
    $competition = Competition::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get("/competitions/$competition->id/edit");

    $response->assertOk();
});
