<?php

namespace Database\Seeders;

use App\Models\Competition;
use Illuminate\Database\Seeder;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        Competition::truncate();

        Competition::factory()
            ->count(5)
            ->create();
    }
}
