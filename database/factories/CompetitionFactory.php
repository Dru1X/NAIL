<?php

namespace Database\Factories;

use App\Enums\StageType;
use App\Models\Competition;
use App\Models\Stage;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

class CompetitionFactory extends Factory
{
    protected $model = Competition::class;

    public function definition(): array
    {
        $startsOn = CarbonImmutable::make(
            fake()->dateTimeBetween('-5 years', '-2 months')
        )->startOfDay();

        $endsOn         = $startsOn->addMonths(4);
        $entriesOpenOn  = $startsOn->subMonth();
        $entriesCloseOn = $startsOn->addWeek();

        $span = ($startsOn->year == $endsOn->year) ?
            $startsOn->year :
            $startsOn->year . '/' . substr($endsOn->year, 2);

        return [
            'name'             => "Indoor League $span",
            'entries_open_on'  => $startsOn,
            'entries_close_on' => $endsOn,
            'starts_on'        => $entriesOpenOn,
            'ends_on'          => $entriesCloseOn,
            'created_at'       => CarbonImmutable::now(),
            'updated_at'       => CarbonImmutable::now(),
        ];
    }

    // Helpers ----

    public function withStages(): self
    {
        return $this->has(
            Stage::factory()
                ->count(2)
                ->state(new Sequence(
                    ['type' => StageType::League],
                    ['type' => StageType::Playoff],
                ))
        );
    }
}
