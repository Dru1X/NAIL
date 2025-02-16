<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\Entry;
use App\Models\Standing;
use Illuminate\Database\Seeder;

class EntrySeeder extends Seeder
{
    public function run(): void
    {
        foreach (Competition::all() as $competition) {
            if ($competition->entries()->count() > 0) {
                continue;
            }

            $leagueStage = $competition->leagueStage;

            Entry::factory()
                ->for($competition)
                ->count(rand(0, $leagueStage->capacity))
                ->create()
                ->each(function (Entry $entry) use ($leagueStage) {
                    Standing::factory()
                        ->for($leagueStage, 'stage')
                        ->for($entry)
                        ->create();
                });
        }
    }
}
