<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Models\Person;
use App\Services\PersonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PersonController extends Controller
{
    public function __construct(protected PersonService $personService) {}

    public function index(): View
    {
        $people = $this->personService->getPeople();

        return view('people.index', compact('people'));
    }

    public function create(): View
    {
        return view('people.create');
    }

    public function store(PersonRequest $request): RedirectResponse
    {
        $this->personService->addPerson($request->validated());

        return redirect()->route('people.index');
    }

    public function show(Person $person) {}

    public function edit(Person $person): View
    {
        return view('people.edit', compact('person'));
    }

    public function update(PersonRequest $request, Person $person): RedirectResponse
    {
        $this->personService->updatePerson($person, $request->validated());

        return redirect()->route('people.index');
    }

    public function destroy(Person $person): RedirectResponse
    {
        $this->personService->removePerson($person);

        return redirect()->route('people.index');
    }
}
