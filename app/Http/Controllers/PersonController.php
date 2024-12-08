<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Services\PersonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PersonController extends Controller
{
    public function __construct(protected PersonService $personService) {}

    public function index(): View
    {
        $people = $this->personService->getPeople();

        return view('people.index', compact('people'));
    }

    public function create() {}

    public function store(Request $request) {}

    public function show(Person $person) {}

    public function edit(Person $person) {}

    public function update(Request $request, Person $person) {}

    public function destroy(Person $person): RedirectResponse
    {
        $this->personService->removePerson($person);

        return redirect()->route('people.index');
    }
}
