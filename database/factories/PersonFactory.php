<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        $firstName = $this->faker->firstName();
        $lastName  = $this->faker->lastName();

        return [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'full_name'  => $firstName . ' ' . $lastName,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
