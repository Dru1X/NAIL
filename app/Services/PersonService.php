<?php

namespace App\Services;

use App\Models\Person;
use Illuminate\Support\Collection;

class PersonService
{
    public function findPerson(int $id): ?Person
    {
        return Person::find($id);
    }

    /**
     * @return Collection<int, Person>
     */
    public function getPeople(): Collection
    {
        return Person::orderBy('first_name')
            ->orderBy('last_name')
            ->orderBy('id')
            ->get();
    }

    public function addPerson(array $data): Person
    {
        $fullName = trim(implode(" ", [$data['first_name'], $data['last_name']]));

        return Person::create(array_merge($data, [
            'full_name' => $fullName,
        ]));
    }

    public function updatePerson(Person $person, array $data): Person
    {
        $fullName = trim(implode(" ", [$data['first_name'], $data['last_name']]));

        $person->update(array_merge($data, [
            'full_name' => $fullName,
        ]));

        return $person;
    }

    public function removePerson(Person $person): bool
    {
        return $person->delete();
    }
}
