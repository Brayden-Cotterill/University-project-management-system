<?php

namespace Database\Factories;

use App\Models\ProjectSupervisor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class ModuleLeaderFactory creates instances of a module leader
 * @extends Factory<ProjectSupervisor>
 */
class ModuleLeaderFactory extends Factory
{
    /**
     * Define the model's default state.
     *This creates an instance of a user (with user_type set to module leader)
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(['user_type' => 'module_leader']),
        ];
    }
}
