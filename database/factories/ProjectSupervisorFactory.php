<?php

namespace Database\Factories;

use App\Models\ProjectSupervisor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * A factory for creating project supervisor models
 * @extends Factory<ProjectSupervisor>
 */
class ProjectSupervisorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * This creates an instance of a user (with user_type set to project_supervisor) with the following:
     * max_student_assign is a random number between 3 and 10
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(['user_type' => 'project_supervisor']),
            'max_student_assign' => fake()->numberBetween(3, 10)
        ];
    }
}
