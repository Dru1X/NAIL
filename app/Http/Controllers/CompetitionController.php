<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompetitionRequest;
use App\Models\Competition;
use App\Services\CompetitionService;
use App\Services\EntryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CompetitionController extends Controller
{
    public function __construct(
        protected CompetitionService $competitionService,
        protected EntryService       $entryService,
    ) {}

    public function index(): View
    {
        Gate::authorize('viewAny', Competition::class);

        $competitions = $this->competitionService->getCompetitions();

        return view('competitions.index', compact('competitions'));
    }

    public function create(): View
    {
        Gate::authorize('create', Competition::class);

        return view('competitions.create');
    }

    public function store(CompetitionRequest $request): RedirectResponse
    {
        Gate::authorize('create', Competition::class);

        $this->competitionService->addCompetition($request->validated());

        return redirect()->route('competitions.index');
    }

    public function show(Competition $competition): View
    {
        Gate::authorize('view', $competition);

        $competition = $this->competitionService->findCompetition($competition->id);
        $entries     = $this->entryService->getCompetitionEntries($competition);

        return view('competitions.show', [
            'competition' => $competition,
            'entries'     => $entries,
        ]);
    }

    public function edit(Competition $competition): View
    {
        Gate::authorize('update', $competition);

        return view('competitions.edit', [
            'competition' => $this->competitionService->findCompetition($competition->id),
        ]);
    }

    public function update(CompetitionRequest $request, Competition $competition): RedirectResponse
    {
        Gate::authorize('update', $competition);

        $this->competitionService->updateCompetition($competition, $request->validated());

        return redirect()->route('competitions.index');
    }

    public function destroy(Competition $competition): RedirectResponse
    {
        Gate::authorize('delete', $competition);

        $this->competitionService->removeCompetition($competition);

        return redirect()->route('competitions.index');
    }
}
