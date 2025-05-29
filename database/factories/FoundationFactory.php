<?php

namespace Database\Factories;

use App\Models\Foundation;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoundationFactory extends Factory
{
    protected $model = Foundation::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'strasse' => $this->faker->streetAddress(),
            'ort' => $this->faker->city(),
            'land' => $this->faker->country(),
            'nextCouncilMeeting' => $this->faker->dateTimeBetween('+1 week', '+2 months'),
        ];
    }
}