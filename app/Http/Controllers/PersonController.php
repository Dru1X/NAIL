<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Models\Person;
use App\Services\PersonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PersonController extends Controller
{
    public function __construct(protected PersonService $personService) {}

    public function index(): View
    {
        Gate::authorize('viewAny', Person::class);

        $people = $this->personService->getPeople();

        return view('people.index', compact('people'));
    }

    public function create(): View
    {
        Gate::authorize('create', Person::class);

        return view('people.create');
    }

    public function store(PersonRequest $request): RedirectResponse
    {
        Gate::authorize('create', Person::class);

        $this->personService->addPerson($request->validated());

        return redirect()->route('people.index');
    }

    public function show(Person $person): View
    {
        Gate::authorize('view', $person);

        return view('people.show', compact('person'));
    }

    public function edit(Person $person): View
    {
        Gate::authorize('update', $person);

        return view('people.edit', compact('person'));
    }

    public function update(PersonRequest $request, Person $person): RedirectResponse
    {
        Gate::authorize('update', $person);

        $this->personService->updatePerson($person, $request->validated());

        return redirect()->route('people.index');
    }

    public function destroy(Person $person): RedirectResponse
    {
        Gate::authorize('delete', $person);

        $this->personService->removePerson($person);

        return redirect()->route('people.index');
    }
}
