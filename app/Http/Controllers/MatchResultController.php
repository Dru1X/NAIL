<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatchResultRequest;
use App\Models\Competition;
use App\Models\MatchResult;
use App\Models\Stage;
use App\Services\EntryService;
use App\Services\MatchResultService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MatchResultController extends Controller
{
    public function __construct(
        protected EntryService       $entryService,
        protected MatchResultService $matchResultService,
    ) {}

    public function index(Competition $competition, Stage $stage): View
    {
        $matches = $this->matchResultService->getMatchResultsForStage($stage);

        return view('matches.index', [
            'competition' => $competition,
            'stage'       => $stage,
            'matches'     => $matches,
        ]);
    }

    public function create(Competition $competition, Stage $stage): View
    {
        $entries = $this->entryService->getCompetitionEntries($competition);

        return view('matches.create', [
            'competition' => $competition,
            'stage'       => $stage,
            'entries'     => $entries,
        ]);
    }

    public function store(MatchResultRequest $request, Competition $competition, Stage $stage): RedirectResponse
    {
        $data = $request->validated();
        $this->matchResultService->recordMatchResult($stage, $data);

        return redirect()->route('competitions.show', $competition);
    }

    public function show(Competition $competition, Stage $stage, MatchResult $match): View
    {
        return view('matches.show', [
            'competition' => $competition,
            'stage'       => $stage,
            'match'       => $match,
        ]);
    }

    public function edit(Competition $competition, Stage $stage, MatchResult $match): View
    {
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
        $data = $request->validated();
        $this->matchResultService->updateMatchResult($match, $stage, $data);

        return redirect()->route('competitions.show', $competition);
    }

    public function destroy(Competition $competition, Stage $stage, MatchResult $match): RedirectResponse
    {
        $this->matchResultService->removeMatchResult($match);

        return redirect()->route('competitions.show', $competition);
    }
}
