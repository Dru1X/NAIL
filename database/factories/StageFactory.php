<?php

namespace Database\Factories;

use App\Enums\StageType;
use App\Models\Competition;
use App\Models\Stage;
use Carbon\CarbonImmutable;
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

    public function startingOn(CarbonImmutable $startsOn): static
    {
        return $this->state(fn() => [
            'starts_on' => $startsOn,
        ]);
    }

    public function league(): static
    {
        return $this->state(fn(array $attributes) => [
            'type'    => StageType::League,
            'ends_on' => $attributes['starts_on']->addWeeks(12),
        ]);
    }

    public function playoff(): static
    {
        return $this->state(fn(array $attributes) => [
            'type'    => StageType::Playoff,
            'ends_on' => $attributes['starts_on'],
        ]);
    }
}
