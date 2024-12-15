<?php

namespace Database\Factories;

use App\Enums\BowStyle;
use App\Models\Competition;
use App\Models\Entry;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EntryFactory extends Factory
{
    protected $model = Entry::class;

    public function definition(): array
    {
        $initialHandicap = fake()->optional(0.8)->numberBetween(1, 150);
        $currentHandicap = fake()->optional($initialHandicap ? 1 : 0)->numberBetween(0, $initialHandicap);

        return [
            'competition_id'   => Competition::factory(),
            'person_id'        => Person::factory(),
            'bow_style'        => fake()->randomElement(BowStyle::cases()),
            'initial_handicap' => $initialHandicap,
            'current_handicap' => $currentHandicap,
            'created_at'       => Carbon::now(),
            'updated_at'       => Carbon::now(),
        ];
    }
}
