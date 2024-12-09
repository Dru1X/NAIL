<?php

namespace App\Services;

use App\Models\Person;
use Illuminate\Support\Collection;

class PersonService
{
    // Lookup ----

    /**
     * Get all people
     *
     * @return Collection<int, Person>
     */
    public function getPeople(): Collection
    {
        return Person::orderBy('first_name')
            ->orderBy('last_name')
            ->orderBy('id')
            ->get();
    }

    /**
     * Find a single person by their unique ID
     */
    public function findPerson(int $id): ?Person
    {
        return Person::find($id);
    }

    // Management ----

    /**
     * Add a new person
     */
    public function addPerson(array $data): Person
    {
        $fullName = trim(implode(" ", [$data['first_name'], $data['last_name']]));

        return Person::create(array_merge($data, [
            'full_name' => $fullName,
        ]));
    }

    /**
     * Update an existing person
     */
    public function updatePerson(Person $person, array $data): Person
    {
        $fullName = trim(implode(" ", [$data['first_name'], $data['last_name']]));

        $person->update(array_merge($data, [
            'full_name' => $fullName,
        ]));

        return $person;
    }

    /**
     * Remove an existing person
     */
    public function removePerson(Person $person): bool
    {
        return $person->delete();
    }
}
