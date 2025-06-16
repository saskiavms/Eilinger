<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition(): array
    {
        return [
            'currency' => $this->faker->randomElement(['Swiss Franc', 'Euro', 'US Dollar']),
            'abbreviation' => $this->faker->randomElement(['CHF', 'EUR', 'USD']),
            'symbol' => $this->faker->randomElement(['CHF', '€', '$']),
            'is_pinned' => false,
        ];
    }
}