<?php

namespace Database\Factories;

use App\Models\Round;
use App\Models\Stage;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Carbon;

class RoundFactory extends Factory
{
    protected $model = Round::class;

    public function definition(): array
    {
        $startsOn = CarbonImmutable::make(
            fake()->dateTimeBetween('-5 years', '-2 months')
        )->startOfDay();

        return [
            'stage_id'   => Stage::factory(),
            'name'       => 'Round ' . fake()->numberBetween(1, 20),
            'starts_on'  => $startsOn,
            'ends_on'    => $startsOn->addWeek(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    // State ----

    public function on(CarbonInterface $date): static
    {
        return $this->state(fn() => [
            'starts_on' => $date,
            'ends_on'   => $date,
        ]);
    }

    public function between(CarbonPeriod $period): static
    {
        return $this->state(new Sequence(
            ...iterator_to_array($period->map(fn(CarbonInterface $intervalDate) => [
                'starts_on' => $intervalDate,
                'ends_on' => $intervalDate->add($period->getDateInterval())->subDay(),
            ]))
        ));
    }

    public function numbered(): static
    {
        return $this->state(new Sequence(
            fn(Sequence $sequence) => [
                'name' => 'Round ' . $sequence->index + 1
            ],
        ));
    }
}
