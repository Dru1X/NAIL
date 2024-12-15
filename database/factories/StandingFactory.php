<?php

namespace Database\Factories;

use App\Models\Entry;
use App\Models\Stage;
use App\Models\Standing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StandingFactory extends Factory
{
    protected $model = Standing::class;

    public function definition(): array
    {
        $matchesPlayed = fake()->numberBetween(0, 30);
        $matchesWon    = fake()->numberBetween(0, $matchesPlayed);
        $matchesLost   = fake()->numberBetween(0, ($matchesPlayed - $matchesWon));
        $matchesDrawn  = $matchesPlayed - $matchesWon - $matchesLost;

        $bonusPoints = fake()->numberBetween(0, $matchesWon);

        return [
            'stage_id'              => Stage::factory(),
            'entry_id'              => Entry::factory(),
            'handicap'              => fake()->numberBetween(1, 150),
            'matches_played'        => $matchesPlayed,
            'matches_won'           => $matchesWon,
            'matches_drawn'         => $matchesDrawn,
            'matches_lost'          => $matchesLost,
            'match_points'          => $matchesPlayed * 135,
            'match_points_adjusted' => $matchesPlayed * 1440,
            'bonus_points'          => $bonusPoints,
            'league_points'         => ($matchesWon * 2) + $matchesDrawn + $bonusPoints,
            'created_at'            => Carbon::now(),
            'updated_at'            => Carbon::now(),
        ];
    }
}
