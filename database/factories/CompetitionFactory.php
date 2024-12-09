<?php

namespace Database\Factories;

use App\Models\Competition;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CompetitionFactory extends Factory
{
    protected $model = Competition::class;

    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('-5 years', '-2 months');
        $endsAt   = CarbonImmutable::parse($startsAt)->addMonths(4);

        $entriesOpenAt  = CarbonImmutable::parse($startsAt)->subMonth();
        $entriesCloseAt = CarbonImmutable::parse($startsAt)->addWeek();

        return [
            'name'             => fake()->name(),
            'entries_open_at'  => $startsAt,
            'entries_close_at' => $endsAt,
            'starts_at'        => $entriesOpenAt,
            'ends_at'          => $entriesCloseAt,
            'created_at'       => CarbonImmutable::now(),
            'updated_at'       => CarbonImmutable::now(),
        ];
    }
}
