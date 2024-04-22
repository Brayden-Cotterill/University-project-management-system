<?php

namespace Database\Factories;

use App\Models\Interest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * InterestFactory creates instances of a new random interest
 * @extends Factory<Interest>
 */
class InterestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * This creates an instance of a interest with a fake unique name
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'interest_name' => fake()->unique()->words(2, true)
        ];
    }
}
