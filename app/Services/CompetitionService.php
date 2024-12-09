<?php

namespace App\Services;

use App\Models\Competition;
use Illuminate\Support\Collection;

class CompetitionService
{
    // Lookup ----

    /**
     * Get all competitions
     *
     * @return Collection<int, Competition>
     */
    public function getCompetitions(): Collection
    {
        return Competition::query()
            ->orderBy('starts_at', 'desc')
            ->orderBy('id')
            ->get();
    }

    /**
     * Find a single competition by its unique ID
     */
    public function findCompetition(int $id): ?Competition
    {
        return Competition::find($id);
    }

    // Management ----

    /**
     * Add a new competition
     */
    public function addCompetition(array $data): Competition
    {
        return Competition::create($data);
    }

    /**
     * Update an existing competition
     */
    public function updateCompetition(Competition $competition, array $data): Competition
    {
        $competition->update($data);

        return $competition;
    }

    /**
     * Remove an existing competition
     */
    public function removeCompetition(Competition $competition): bool
    {
        return $competition->delete();
    }
}
