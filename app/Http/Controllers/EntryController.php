<?php

namespace App\Http\Controllers;

use App\Enums\BowStyle;
use App\Http\Requests\EntryRequest;
use App\Models\Competition;
use App\Models\Entry;
use App\Services\EntryService;
use App\Services\PersonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EntryController extends Controller
{
    public function __construct(protected PersonService $personService, protected EntryService $entryService) {}

    public function index(Competition $competition) {}

    public function create(Competition $competition): View
    {
        $people = $this->personService->getPeople();

        return view('competitions.entries.create', [
            'competition' => $competition,
            'entries'     => $competition->entries,
            'people'      => $people,
            'bowStyles'   => BowStyle::cases(),
        ]);
    }

    public function store(EntryRequest $request, Competition $competition): RedirectResponse
    {
        $this->entryService->enterCompetition($competition, $request->validated());

        return redirect()->route('competitions.show', [$competition]);
    }

    public function show(Competition $competition, Entry $entry) {}

    public function edit(Competition $competition, Entry $entry) {}

    public function update(EntryRequest $request, Competition $competition, Entry $entry) {}

    public function destroy(Competition $competition, Entry $entry) {}
}
