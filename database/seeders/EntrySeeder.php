<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\Entry;
use Illuminate\Database\Seeder;

class EntrySeeder extends Seeder
{
    public function run(): void
    {
        foreach(Competition::all() as $competition) {
            if($competition->entries()->count() > 0) {
                continue;
            }

            Entry::factory()
                ->for($competition)
                ->forPerson()
                ->count(rand(10, 20))
                ->create();
        }
    }
}
