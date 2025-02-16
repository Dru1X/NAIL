<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatchResultRequest;
use App\Models\Competition;
use App\Models\MatchResult;
use App\Models\Stage;
use App\Services\CompetitionService;
use App\Services\EntryService;
use App\Services\MatchResultService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MatchResultController extends Controller
{
    public function __construct(
        protected CompetitionService $competitionService,
        protected EntryService       $entryService,
        protected MatchResultService $matchResultService,
    ) {}

    public function index(Competition $competition, Stage $stage): View
    {
        Gate::authorize('viewAny', MatchResult::class);

        $rounds  = $this->competitionService->getRoundsForStage($stage);
        $matches = $this->matchResultService->getMatchResultsForStage($stage);

        return view('matches.index', [
            'competition' => $competition,
            'stage'       => $stage,
            'rounds'      => $rounds,
            'matches'     => $matches,
        ]);
    }

    public function create(Competition $competition, Stage $stage): View
    {
        Gate::authorize('create', MatchResult::class);

        $entries = $this->entryService->getCompetitionEntries($competition);

        return view('matches.create', [
            'competition' => $competition,
            'stage'       => $stage,
            'entries'     => $entries,
        ]);
    }

    public function store(MatchResultRequest $request, Competition $competition, Stage $stage): RedirectResponse
    {
        Gate::authorize('create', MatchResult::class);

        $this->matchResultService->recordMatchResult($stage, $request->validatedData());

        return redirect()->route('competitions.show', $competition);
    }

    public function show(Competition $competition, Stage $stage, MatchResult $match): View
    {
        Gate::authorize('view', $match);

        return view('matches.show', [
            'competition' => $competition,
            'stage'       => $stage,
            'match'       => $match,
        ]);
    }

    public function edit(Competition $competition, Stage $stage, MatchResult $match): View
    {
        Gate::authorize('update', $match);

        $entries = $this->entryService->getCompetitionEntries($competition);

        return view('matches.edit', [
            'competition' => $competition,
            'stage'       => $stage,
            'entries'     => $entries,
            'match'       => $match,
        ]);
    }

    public function update(MatchResultRequest $request, Competition $competition, Stage $stage, MatchResult $match): RedirectResponse
    {
        Gate::authorize('update', $match);

        $this->matchResultService->updateMatchResult($match, $stage, $request->validatedData());

        return redirect()->route('competitions.show', $competition);
    }

    public function destroy(Competition $competition, Stage $stage, MatchResult $match): RedirectResponse
    {
        Gate::authorize('delete', $match);

        $this->matchResultService->removeMatchResult($match);

        return redirect()->route('competitions.show', $competition);
    }
}
