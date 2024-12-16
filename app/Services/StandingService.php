<?php

namespace App\Services;

use App\Enums\StageType;
use App\Models\Competition;
use App\Models\Entry;
use App\Models\Stage;
use App\Models\Standing;
use DomainException;
use Illuminate\Support\Collection;
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

}
