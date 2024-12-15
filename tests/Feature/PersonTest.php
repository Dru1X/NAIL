<?php

use App\Models\Person;
use App\Models\User;

test('empty people list is displayed', function () {
    $user = User::factory()->create();

    Person::query()->delete();

    $response = $this
        ->actingAs($user)
        ->get('/people');

    $response->assertOk();
});

test('populated people list is displayed', function () {
    $user = User::factory()->create();

    Person::factory()
        ->count(10)
        ->create();

    $response = $this
        ->actingAs($user)
        ->get('/people');

    $response->assertOk();
});

test('person information is displayed', function () {
    $user = User::factory()->create();

    $person = Person::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get("/people/$person->id");

    $response->assertOk();
});

test('new person form is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/people/create');

    $response->assertOk();
});

test('new person can be added', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/people', [
            'first_name' => 'John',
            'last_name'  => 'Doe',
        ]);

    $response->assertRedirect('/people');

    $this->assertDatabaseHas('people', [
        'first_name' => 'John',
        'last_name'  => 'Doe',
    ]);
});

test('new person requires first name', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/people', [
            'first_name' => null,
            'last_name'  => 'Doe',
        ]);

    $response->assertInvalid(['first_name']);
});

test('new person requires last name', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/people', [
            'first_name' => 'John',
            'last_name'  => null,
        ]);

    $response->assertInvalid(['last_name']);
});

test('person editing form is displayed', function () {
    $user   = User::factory()->create();
    $person = Person::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get("/people/$person->id/edit");

    $response->assertOk();
});

test('person can be updated', function () {
    $user   = User::factory()->create();
    $person = Person::factory()->create();

    $response = $this
        ->actingAs($user)
        ->put("/people/$person->id", [
            'first_name' => 'John',
            'last_name'  => 'Doe',
        ]);

    $response->assertRedirect('/people');

    $this->assertDatabaseHas('people', [
        'first_name' => 'John',
        'last_name'  => 'Doe',
    ]);
});

test('updated person requires first name', function () {
    $user   = User::factory()->create();
    $person = Person::factory()->create();

    $response = $this
        ->actingAs($user)
        ->put("/people/$person->id", [
            'first_name' => null,
            'last_name'  => 'Doe',
        ]);

    $response->assertInvalid(['first_name']);
});

test('updated person requires last name', function () {
    $user   = User::factory()->create();
    $person = Person::factory()->create();

    $response = $this
        ->actingAs($user)
        ->put("/people/$person->id", [
            'first_name' => 'John',
            'last_name'  => null,
        ]);

    $response->assertInvalid(['last_name']);
});

test('person can be removed', function () {
    $user   = User::factory()->create();
    $person = Person::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete("/people/$person->id");

    $response->assertRedirect('/people');

    $this->assertSoftDeleted('people', [
        'id'         => $person->id,
        'first_name' => $person->first_name,
        'last_name'  => $person->last_name,
    ]);
});
