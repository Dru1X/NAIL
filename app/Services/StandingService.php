<?php

namespace App\Services;

use App\Enums\StageType;
use App\Models\Competition;
use App\Models\Entry;
use App\Models\MatchResult;
use App\Models\Score;
use App\Models\Stage;
use App\Models\Standing;
use DomainException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class StandingService
{
    // Lookup ----

    /**
     * Get all league standings for a competition
     *
     * @return Collection<int, Standing>
     */
    public function getLeagueStandingsForCompetition(Competition $competition): Collection
    {
        $stage = $competition->stages->firstWhere('type', StageType::League);

        return $stage
            ->standings()
            ->with(['entry', 'entry.person'])
            ->get();
    }

    // Management ----

    /**
     * Add a standing for an entry that has qualified for a stage
     */
    public function addStandingForStage(Stage $stage, Entry $entry): Standing
    {
        if ($entry->competition->isNot($stage->competition)) {
            throw new InvalidArgumentException("Cannot add standing for an entry into a different competition");
        }

        if ($stage->standings()->whereEntryId($entry->id)->exists()) {
            throw new DomainException("A standing already exists for that entry and stage");
        }

        return $stage
            ->standings()
            ->create(['entry_id' => $entry->id]);
    }

    /**
     * Add the given match result to the competitor standings for the appropriate stage
     */
    public function applyMatchResult(MatchResult $match): void
    {
        /** @var Score[] $scores */
        $scores = [$match->leftScore, $match->rightScore];

        DB::transaction(function () use ($match, $scores) {
            foreach ($scores as $score) {
                $standing = $score->entry
                    ->standings()
                    ->whereStageId($match->round->stage_id)
                    ->firstOrFail();

                $this->aggregateMatchScoreIntoStanding($match, $score, $standing);
            }
        });
    }

    // Internals ----

    /**
     * Aggregate a match score into the given standing
     */
    protected function aggregateMatchScoreIntoStanding(MatchResult $match, Score $score, Standing $standing): void
    {
        $isWinner = $match->has_winner && $match->winner->is($score->entry);
        $isLoser  = $match->has_winner && $match->winner->isNot($score->entry);

        Standing::whereId($standing->id)->incrementEach([
            'matches_played'        => 1,
            'matches_won'           => (int)$isWinner,
            'matches_drawn'         => (int)$match->is_draw,
            'matches_lost'          => (int)$isLoser,
            'match_points'          => $score->match_points,
            'match_points_adjusted' => $score->match_points_adjusted,
            'bonus_points'          => $score->bonus_points,
            'league_points'         => $score->league_points,
            'total_points'          => $score->bonus_points + $score->league_points,
        ]);
    }
}
