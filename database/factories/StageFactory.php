<?php

namespace Database\Factories;

use App\Enums\StageType;
use App\Models\Competition;
use App\Models\Stage;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StageFactory extends Factory
{
    protected $model = Stage::class;

    public function definition(): array
    {
        $type = fake()->randomElement(StageType::cases());

        $startsOn = CarbonImmutable::make(
            fake()->dateTimeBetween('-5 years', '-2 months')
        )->startOfDay();

        $endsOn = $type == StageType::League ? $startsOn->addWeeks(12) : $startsOn;

        return [
            'competition_id' => Competition::factory(),
            'name'           => ucfirst($type->value) . ' Stage',
            'type'           => $type,
            'capacity'       => fake()->numberBetween(10, 100),
            'starts_on'      => $startsOn,
            'ends_on'        => $endsOn,
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now(),
        ];
    }

    // State ----

    public function on(CarbonInterface $date): static
    {
        return $this->state(fn(array $attributes) => [
            'starts_on' => $date,
            'ends_on'   => $date,
        ]);
    }

    public function between(CarbonPeriod $period): static
    {
        return $this->state(fn(array $attributes) => [
            'starts_on' => $period->start,
            'ends_on'   => $period->end,
        ]);
    }

    public function league(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'League Stage',
            'type' => StageType::League,
        ]);
    }

    public function playoff(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'Playoff Stage',
            'type' => StageType::Playoff,
        ]);
    }
}
