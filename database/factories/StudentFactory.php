<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * StudentFactory creates a factory for a student model
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * This creates an instance of a user (with user_type set to student) with the following:
     * student_user_name: the user_name of the user instance
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(['user_type' => 'student']),
            'student_user_name' => function (array $attributes) {
                return User::find($attributes['user_id'])->user_name;
            },
        ];
    }
}
