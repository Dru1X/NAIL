<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\Round;
use App\Models\Stage;
use Carbon\CarbonInterval;
use Illuminate\Database\Seeder;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        Stage::query()->forceDelete();
        Competition::query()->forceDelete();

        Competition::factory()
            ->count(5)
            ->create()
            ->each(function (Competition $competition) {

                $leaguePeriod = $competition->starts_on->toPeriod(
                    $competition->ends_on->subWeek()->subDay(),
                    CarbonInterval::week()
                );

                $leagueStage = Stage::factory()
                    ->for($competition)
                    ->between($leaguePeriod)
                    ->league()
                    ->create();

                Round::factory()
                    ->for($leagueStage)
                    ->between($leaguePeriod)
                    ->numbered()
                    ->count($leaguePeriod->count())
                    ->create();

                $playoffStage = Stage::factory()
                    ->for($competition)
                    ->on($competition->ends_on)
                    ->playoff()
                    ->createOne();

                foreach (['Quarter Finals', 'Semi-Finals', 'Final'] as $roundName) {
                    Round::factory()
                        ->for($playoffStage)
                        ->on($playoffStage->starts_on)
                        ->createOne(['name' => $roundName]);
                }
            });
    }
}
