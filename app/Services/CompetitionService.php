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
            ->withCount('stages')
            ->orderBy('starts_on', 'desc')
            ->orderBy('id')
            ->get();
    }

    /**
     * Find a single competition by its unique ID
     */
    public function findCompetition(int $id): ?Competition
    {
        return Competition::with('stages')
            ->withCount('stages')
            ->find($id);
    }

    // Management ----

    /**
     * Add a new competition
     */
    public function addCompetition(array $data): Competition
    {
        return DB::transaction(function () use ($data): Competition {

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
        return DB::transaction(function () use ($competition, $data): Competition {
            $competition->update([
                'name'             => $data['name'],
                'entries_open_on'  => CarbonImmutable::make($data['entries_open_on']),
                'entries_close_on' => CarbonImmutable::make($data['entries_close_on']),
            ]);

            $keepStageIds   = array_filter(Arr::pluck($data['stages'], 'id'));
            $existingStages = $competition->stages;

            foreach ($existingStages as $stage) {
                if (!in_array($stage->id, $keepStageIds)) {
                    $this->removeStage($stage);
                }
            }

            foreach ($data['stages'] as $stageData) {
                if ($stageId = $stageData['id'] ?? null) {
                    $this->updateStage($existingStages->find($stageId), $stageData);
                    continue;
                }

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
     * Remove an existing competition
     */
    public function removeCompetition(Competition $competition): bool
    {
        return DB::transaction(function () use ($competition): bool {
            $competition->stages()->each($this->removeStage(...));

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

        $this->addRoundsForStage($stage);

        return $stage;
    }

    /**
     * Update an existing stage
     */
    protected function updateStage(Stage $stage, array $data): Stage
    {
        $type = StageType::from($data['type']);

        $stage->update([
            'name'      => "$type->name Stage",
            'type'      => $type,
            'starts_on' => CarbonImmutable::make($data['starts_on']),
            'ends_on'   => CarbonImmutable::make($data['ends_on']),
        ]);

        if ($stage->wasChanged('type')) {
            $stage->rounds()->delete();
            $this->addRoundsForStage($stage);

            return $stage;
        }

        if ($stage->wasChanged(['starts_on', 'ends_on'])) {
            $this->updateRoundsForStage($stage);
        }

        return $stage;
    }

    /**
     * Remove an existing stage, and all of its rounds
     */
    protected function removeStage(Stage $stage): bool
    {
        $stage->rounds()->delete();

        return $stage->delete();
    }

    /**
     * Add the rounds needed for any type of stage
     *
     * @return Collection<int, Round>
     */
    protected function addRoundsForStage(Stage $stage): Collection
    {
        return match ($stage->type) {
            StageType::League  => $this->addLeagueRounds($stage),
            StageType::Playoff => $this->addPlayoffRounds($stage),
        };
    }

    /**
     * Add all rounds needed for a league stage
     *
     * @return Collection<int, Round>
     */
    protected function addLeagueRounds(Stage $stage): Collection
    {
        $interval   = CarbonInterval::week();
        $period     = $stage->period->setDateInterval($interval);
        $roundCount = $period->count();

        /** @var CarbonInterface $roundStartsOn */
        foreach ($period as $index => $roundStartsOn) {

            $isLastRound = $roundCount == ($index + 1);
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

    /**
     * Update all the rounds needed for any type of stage
     *
     * @return Collection<int, Round>
     */
    protected function updateRoundsForStage(Stage $stage): Collection
    {
        return match ($stage->type) {
            StageType::League  => $this->updateLeagueRounds($stage),
            StageType::Playoff => $this->updatePlayoffRounds($stage),
        };
    }

    /**
     * Update all rounds needed for a league stage
     *
     * @return Collection<int, Round>
     */
    protected function updateLeagueRounds(Stage $stage): Collection
    {
        $interval   = CarbonInterval::week();
        $period     = $stage->period->setDateInterval($interval);
        $roundCount = $period->count();

        $existingRounds = $stage->rounds()->get();

        /** @var CarbonInterface $roundStartsOn */
        foreach ($period as $index => $roundStartsOn) {

            $isLastRound = $roundCount == ($index + 1);
            $roundEndsOn = $isLastRound ? $stage->ends_on : $roundStartsOn->add($interval)->subDay();

            $roundData = [
                'name'      => 'Round ' . $index + 1,
                'starts_on' => $roundStartsOn,
                'ends_on'   => $roundEndsOn,
            ];

            if ($existingRound = $existingRounds->get($index)) {
                $existingRound->update($roundData);
            } else {
                $stage->rounds()->create($roundData);
            }
        }

        $existingRounds
            ->slice($period->count())
            ->each(fn($round) => $round->delete());

        return $stage->rounds()->get();
    }

    /**
     * Update all rounds needed for a playoff stage
     *
     * @return Collection<int, Round>
     */
    protected function updatePlayoffRounds(Stage $stage): Collection
    {
        $stage->rounds()->update([
            'starts_on' => $stage->starts_on,
            'ends_on'   => $stage->ends_on,
        ]);

        return $stage->rounds()->get();
    }
}
