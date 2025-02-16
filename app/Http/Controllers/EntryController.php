<?php

namespace App\Http\Controllers;

use App\Enums\BowStyle;
use App\Http\Requests\EntryRequest;
use App\Models\Competition;
use App\Models\Entry;
use App\Services\EntryService;
use App\Services\PersonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class EntryController extends Controller
{
    public function __construct(
        protected PersonService $personService,
        protected EntryService  $entryService,
    ) {}

    public function index(Competition $competition): View
    {
        Gate::authorize('viewAny', Entry::class);

        $entries = $this->entryService->getCompetitionEntries($competition);

        return view('competitions.entries.index', [
            'competition' => $competition,
            'entries'     => $entries,
        ]);
    }

    public function create(Competition $competition): View
    {
        Gate::authorize('create', Entry::class);

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
        Gate::authorize('create', Entry::class);

        $this->entryService->enterCompetition($competition, $request->validated());

        return redirect()->route('competitions.entries.index', [$competition]);
    }

    public function show(Competition $competition, Entry $entry): View
    {
        Gate::authorize('view', $entry);

        $entry = $this->entryService->findCompetitionEntry($competition, $entry->id);

        return view('competitions.entries.show', [
            'competition' => $competition,
            'entry'       => $entry,
        ]);
    }

    public function edit(Competition $competition, Entry $entry): View
    {
        Gate::authorize('update', $entry);

        $people = $this->personService->getPeople();
        $entry  = $this->entryService->findCompetitionEntry($competition, $entry->id);

        return view('competitions.entries.edit', [
            'entry'       => $entry,
            'competition' => $competition,
            'entries'     => $competition->entries,
            'people'      => $people,
            'bowStyles'   => BowStyle::cases(),
        ]);
    }

    public function update(EntryRequest $request, Competition $competition, Entry $entry): RedirectResponse
    {
        Gate::authorize('update', $entry);

        $this->entryService->updateEntry($entry, $request->validated());

        return redirect()->route('competitions.entries.index', [$competition]);
    }

    public function destroy(Competition $competition, Entry $entry): RedirectResponse
    {
        Gate::authorize('delete', $entry);

        $this->entryService->withdrawFromCompetition($entry);

        return redirect()->route('competitions.entries.index', [$competition]);
    }
}
