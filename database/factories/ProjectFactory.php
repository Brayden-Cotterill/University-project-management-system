<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectSupervisor;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Creates a project factory
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * This creates an instance of a project with the following:
     * A created instance of the projectsupervisor factory
     * A created instance of the student factory
     * two random words as the project name
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_supervisor_id' => ProjectSupervisor::factory(),
            'student_id' => Student::factory(),
            'project_name' => fake()->unique()->words(2, true),
        ];
    }
}
