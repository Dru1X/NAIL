<?php

namespace App\Services;

use App\Enums\StageType;
use App\Models\Competition;
use App\Models\Round;
use App\Models\Stage;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($data) {

            $competition = Competition::create([
                'name'             => $data['name'],
                'entries_open_on'  => CarbonImmutable::make($data['entries_open_on']),
                'entries_close_on' => CarbonImmutable::make($data['entries_close_on']),
                'starts_on'        => now(),
                'ends_on'          => now(),
            ]);

            foreach ($data['stages'] as $stageData) {
                $this->addStage($competition, $stageData);
            }

            $competition->update([
                'starts_on' => $competition->stages->min('starts_on'),
                'ends_on'   => $competition->stages->max('ends_on'),
            ]);

            return $competition;
        });
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
        return DB::transaction(function () use ($competition): bool {
            $competition->stages()->each(function ($stage): void {
                $stage->rounds()->delete();
                $stage->delete();
            });

            return $competition->delete();
        });
    }

    // Internals ----

    /**
     * Add a new stage to a competition
     */
    protected function addStage(Competition $competition, array $data): Stage
    {
        $type = StageType::from($data['type']);

        $stage = $competition->stages()->create([
            'name'      => "$type->name Stage",
            'type'      => $type,
            'capacity'  => $type->defaultCapacity(),
            'starts_on' => CarbonImmutable::make($data['starts_on']),
            'ends_on'   => CarbonImmutable::make($data['ends_on']),
        ]);

        switch ($type) {
            case StageType::League:
                $this->addLeagueRounds($stage);
                break;

            case StageType::Playoff:
                $this->addPlayoffRounds($stage);
                break;
        }

        return $stage;
    }

    /**
     * Add all rounds needed for a league stage
     *
     * @return Collection<int, Round>
     */
    protected function addLeagueRounds(Stage $stage): Collection
    {
        $interval = CarbonInterval::week();
        $period   = $stage->period->setDateInterval($interval);
        $lastDate = $period->last();

        /** @var CarbonInterface $roundStartsOn */
        foreach ($period as $index => $roundStartsOn) {

            $isLastRound = $lastDate == $roundStartsOn;
            $roundEndsOn = $isLastRound ? $stage->ends_on : $roundStartsOn->add($interval)->subDay();

            $stage->rounds()->create([
                'name'      => 'Round ' . $index + 1,
                'starts_on' => $roundStartsOn,
                'ends_on'   => $roundEndsOn,
            ]);
        }

        return $stage->rounds;
    }

    /**
     * Add all rounds needed for a playoff stage
     *
     * @return Collection<int, Round>
     */
    protected function addPlayoffRounds(Stage $stage): Collection
    {
        $roundNames = ['Quarter-Finals', 'Semi-Finals', 'Final'];

        foreach ($roundNames as $roundName) {
            $stage->rounds()->create([
                'name'      => $roundName,
                'starts_on' => $stage->starts_on,
                'ends_on'   => $stage->ends_on,
            ]);
        }

        return $stage->rounds;
    }
}
