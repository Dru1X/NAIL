<?php

namespace App\Services;

use App\Enums\StageType;
use App\Models\Competition;
use App\Models\Stage;
use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
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
            ->orderBy('starts_on', 'desc')
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
        $stages = collect($data['stages'])->map(fn(array $stageData) => Stage::make([
            'name'      => ucfirst($stageData['type']) . ' Stage',
            'type'      => StageType::from($stageData['type']),
            'starts_on' => CarbonImmutable::make($stageData['starts_on']),
            'ends_on'   => CarbonImmutable::make($stageData['ends_on']),
            'capacity'  => StageType::from($stageData['type'])->defaultCapacity(),
        ]));

        $competition = Competition::create([
            'name'             => $data['name'],
            'entries_open_on'  => CarbonImmutable::make($data['entries_open_on']),
            'entries_close_on' => CarbonImmutable::make($data['entries_close_on']),
            'starts_on'        => $stages->min('starts_on'),
            'ends_on'          => $stages->max('ends_on'),
        ]);

        $stages->each(fn(Stage $stage) => $stage
            ->competition()
            ->associate($competition)
            ->save()
        );

        return $competition;
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
