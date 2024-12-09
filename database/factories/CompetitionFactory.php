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
        $startsAt = CarbonImmutable::make(
            fake()->dateTimeBetween('-5 years', '-2 months')
        );

        $endsAt         = $startsAt->addMonths(4);
        $entriesOpenAt  = $startsAt->subMonth();
        $entriesCloseAt = $startsAt->addWeek();

        $span = ($startsAt->year == $endsAt->year) ?
            $startsAt->year :
            $startsAt->year . '/' . substr($endsAt->year, 2);

        return [
            'name'             => "Indoor League $span",
            'entries_open_at'  => $startsAt,
            'entries_close_at' => $endsAt,
            'starts_at'        => $entriesOpenAt,
            'ends_at'          => $entriesCloseAt,
            'created_at'       => CarbonImmutable::now(),
            'updated_at'       => CarbonImmutable::now(),
        ];
    }
}
