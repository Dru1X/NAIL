<?php

namespace Database\Seeders;

use App\Enums\StageType;
use App\Models\Competition;
use App\Models\Entry;
use App\Models\Person;
use App\Models\Standing;
use Illuminate\Database\Seeder;

class EntrySeeder extends Seeder
{
    public function run(): void
    {
        Standing::query()->forceDelete();
        Entry::query()->forceDelete();

        foreach (Competition::all() as $competition) {
            if ($competition->entries()->count() > 0) {
                continue;
            }

            $leagueStage = $competition
                ->stages()
                ->firstWhere('type', StageType::League);

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
