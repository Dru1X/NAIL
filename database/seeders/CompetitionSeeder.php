<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\Stage;
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
                Stage::factory()
                    ->for($competition)
                    ->startingOn($competition->starts_on)
                    ->league()
                    ->createOne();

                Stage::factory()
                    ->for($competition)
                    ->startingOn($competition->ends_on)
                    ->playoff()
                    ->createOne();
            });
    }
}
