<?php

namespace Database\Factories;

use App\Models\ProjectSupervisor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * AdminFactory creates an instance of a new admin
 * @extends Factory<ProjectSupervisor>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * This creates an instance of a user (with user_type set to admin)
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(['user_type' => 'admin']),
        ];
    }
}
