<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\Entry;
use App\Models\Handicap;
use App\Models\MatchResult;
use App\Models\Round;
use App\Models\Score;
use App\Models\Stage;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MatchResultService
{
    // Constants ----

    public const int LEAGUE_POINTS_FOR_WIN = 3;
    public const int LEAGUE_POINTS_FOR_DRAW = 1;
    public const int LEAGUE_POINTS_FOR_LOSS = 0;

    public const int BONUS_POINTS_FOR_HANDICAP_HIT = 1;
    public const int BONUS_POINTS_FOR_CLOSE_LOSS = 1;

    public const int CLOSE_LOSS_THRESHOLD = 5;

    // Setup ----

    public function __construct(
        protected EntryService    $entryService,
        protected HandicapService $handicapService,
        protected StandingService $standingService,
    ) {}

    // Lookup ----

    /**
     * Get all match results for the given competition
     *
     * @return Collection<int, MatchResult>
     */
    public function getMatchResultsForCompetition(Competition $competition): Collection
    {
        return MatchResult::inCompetition($competition)
            ->orderBy('shot_at')
            ->orderBy('id')
            ->get();
    }

    /**
     * Get all match results for the given stage
     *
     * @return Collection<int, MatchResult>
     */
    public function getMatchResultsForStage(Stage $stage): Collection
    {
        return MatchResult::inStage($stage)
            ->orderBy('shot_at')
            ->orderBy('id')
            ->get();
    }

    /**
     * Get all match results for the given round
     *
     * @return Collection<int, MatchResult>
     */
    public function getMatchResultsForRound(Round $round): Collection
    {
        return MatchResult::whereRoundId($round->id)
            ->orderBy('shot_at')
            ->orderBy('id')
            ->get();
    }

    // Management ----

    /**
     * Record a new match result
     */
    public function recordMatchResult(Stage $stage, array $data): MatchResult
    {
        return DB::transaction(function () use ($stage, $data): MatchResult {

            // Parse the match date/time
            $shotAt = CarbonImmutable::make($data['shot_at']);

            // Make a new match result
            $match = MatchResult::make(['shot_at' => $shotAt]);

            // Determine the round in which this match took place
            $round = $this->resolveRound($stage, $shotAt);
            $match->round()->associate($round);

            // Associate the individual scores with the match result
            $leftScore = $this->recordScore($data['left_score']);
            $match->leftScore()->associate($leftScore);

            $rightScore = $this->recordScore($data['right_score']);
            $match->rightScore()->associate($rightScore);

            // Apply domain rules
            $this->preventSoloMatches($leftScore, $rightScore);
            $this->preventDuplicateMatches($stage, $leftScore, $rightScore);

            // Handle a drawn on decisive match outcome
            if ($leftScore->match_points_adjusted === $rightScore->match_points_adjusted) {
                $this->handleDrawnMatch($match, $leftScore, $rightScore);
            } else {
                $this->handleDecisiveMatch($match, $leftScore, $rightScore);
            }

            // Store the result
            $match->save();

            // Aggregate the match result into the standings for the competitors
            $this->standingService->applyMatchResult($match);

            return $match;
        });
    }

    /**
     * Update an existing match result
     *
     * This does not currently cause an update to the standings!
     */
    public function updateMatchResult(MatchResult $match, Stage $stage, array $data): MatchResult
    {
        return DB::transaction(function () use ($match, $stage, $data): MatchResult {

            // Parse the match date/time
            $shotAt = CarbonImmutable::make($data['shot_at']);

            // Determine the round in which this match took place
            $round = $this->resolveRound($stage, $shotAt);
            $match->round()->associate($round);

            // Update the individual scores
            $leftScore  = $this->updateScore($match->leftScore, $data['left_score']);
            $rightScore = $this->updateScore($match->rightScore, $data['right_score']);

            // Apply domain rules
            $this->preventSoloMatches($leftScore, $rightScore);
            $this->preventDuplicateMatches($stage, $leftScore, $rightScore);

            // Handle a drawn on decisive match outcome
            if ($leftScore->match_points_adjusted === $rightScore->match_points_adjusted) {
                $this->handleDrawnMatch($match, $leftScore, $rightScore);
            } else {
                $this->handleDecisiveMatch($match, $leftScore, $rightScore);
            }

            // Update the result
            $match->update(['shot_at' => $shotAt]);

            return $match;
        });
    }

    /**
     * Remove an existing match result
     */
    public function removeMatchResult(MatchResult $match): bool
    {
        return DB::transaction(function () use ($match): bool {
            $matchDeleted = $match->delete();

            $match->leftScore()->delete();
            $match->rightScore()->delete();

            return $matchDeleted;
        });
    }

    // Internals ----

    /**
     * Find the round in which a match was shot
     */
    protected function resolveRound(Stage $stage, CarbonInterface $shotAt): Round
    {
        return $stage->rounds()
            ->whereDate('starts_on', '<=', $shotAt)
            ->whereDate('ends_on', '>=', $shotAt)
            ->firstOrFail();
    }

    /**
     * Prevent matches that involve the same person on both sides
     */
    protected function preventSoloMatches(Score $leftScore, Score $rightScore): void
    {
        if ($leftScore->entry->is($rightScore->entry)) {
            throw new DomainException("A match must involve two different people");
        }
    }

    /**
     * Prevent the same two people competing against each other more than once in the same stage
     */
    protected function preventDuplicateMatches(Stage $stage, Score $leftScore, Score $rightScore, ?MatchResult $exclude = null): void
    {
        $isDuplicateMatch = MatchResult::inStage($stage)
            ->shotByBoth($leftScore->entry, $rightScore->entry)
            ->when($exclude, fn(Builder|MatchResult $match) => $match->whereNot('id', $exclude->id))
            ->exists();

        if ($isDuplicateMatch) {
            throw new DomainException("Two people may only compete against each other once per stage");
        }
    }

    /**
     * Record an individual score for a match competitor
     */
    protected function recordScore(array $data): Score
    {
        $entry = Entry::findOrFail($data['entry_id']);

        $handicap = Handicap::whereBowStyle($entry->bow_style)
            ->whereNumber($entry->current_handicap)
            ->firstOrFail();

        $adjustedPoints = $data['match_points'] + $handicap->match_allowance;

        $newHandicap = $this->handicapService->recalculateHandicap($handicap, $data['match_points']);

        $score = Score::make([
            'handicap_before'       => $handicap->number,
            'handicap_after'        => $newHandicap->number,
            'allowance'             => $handicap->match_allowance,
            'match_points'          => $data['match_points'],
            'match_points_adjusted' => $adjustedPoints,
            'bonus_points'          => $adjustedPoints >= 1440 ? self::BONUS_POINTS_FOR_HANDICAP_HIT : 0,
            'league_points'         => 0, #This gets added when the scores are compared
        ]);

        $score->entry()->associate($entry);

        $score->save();

        if ($score->handicap_after < $score->handicap_before) {
            $this->entryService->improveEntryHandicap($entry, $newHandicap->number);
        }

        return $score;
    }

    /**
     * Update an existing individual score for a match competitor
     *
     * This does not currently cause an update to the entry's current handicap!
     */
    protected function updateScore(Score $score, array $data): Score
    {
        $entry = Entry::findOrFail($data['entry_id']);
        $score->entry()->associate($entry);

        $matchShotAt = $score->matchResult->shot_at;

        $handicapNumber = $entry
            ->scores()
            ->whereHas('matchResult', fn(Builder|MatchResult $match) => $match->shotBefore($matchShotAt))
            ->orderBy('handicap_after')
            ->value('handicap_after');

        $handicap = Handicap::whereBowStyle($entry->bow_style)
            ->whereNumber($handicapNumber)
            ->firstOrFail();

        $adjustedPoints = $data['match_points'] + $handicap->match_allowance;

        $newHandicap = $this->handicapService->recalculateHandicap($handicap, $data['match_points']);

        $score->update([
            'handicap_before'       => $handicap->number,
            'handicap_after'        => $newHandicap->number,
            'allowance'             => $handicap->match_allowance,
            'match_points'          => $data['match_points'],
            'match_points_adjusted' => $adjustedPoints,
            'bonus_points'          => $adjustedPoints >= 1440 ? self::BONUS_POINTS_FOR_HANDICAP_HIT : 0,
            'league_points'         => 0, #This gets added when the scores are compared
        ]);

        return $score;
    }

    /**
     * Handle a match ending in a draw
     */
    protected function handleDrawnMatch(MatchResult $match, Score $leftScore, Score $rightScore): void
    {
        $leftScore->update(['league_points' => self::LEAGUE_POINTS_FOR_DRAW]);
        $rightScore->update(['league_points' => self::LEAGUE_POINTS_FOR_DRAW]);

        $match->winner()->dissociate();
    }

    /**
     * Handle a match ending with a winner
     */
    protected function handleDecisiveMatch(MatchResult $match, Score $leftScore, Score $rightScore): void
    {
        /** @var Collection<int, Score> $scores */
        $scores = collect([$leftScore, $rightScore])->sortByDesc('match_points_adjusted');

        // Process the winning score
        $winningScore = $scores->first();
        $winningScore->update(['league_points' => self::LEAGUE_POINTS_FOR_WIN]);

        $match->winner()->associate($winningScore->entry);

        // Process the losing score
        $losingScore = $scores->last();
        $losingScore->update(['league_points' => self::LEAGUE_POINTS_FOR_LOSS]);

        $matchPointsDifference = $winningScore->match_points_adjusted - $losingScore->match_points_adjusted;

        if ($matchPointsDifference <= self::CLOSE_LOSS_THRESHOLD) {
            $losingScore->increment('bonus_points', self::BONUS_POINTS_FOR_CLOSE_LOSS);
        }
    }
}
