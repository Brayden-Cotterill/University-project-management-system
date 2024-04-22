<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Class UserFactory uses the factory pattern to define example users
 * In other words: this is the 'instructions' Laravel will use if you want to create a test user for the DB
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;


    /**
     * Define the model's default state.
     * Faker PHP used to create example names and usernames
     *
     * $first_name and $surname are defined before the return function
     * in order to get the concat of both when each factory is made
     * @return array<string, mixed>
     * The password for all Users is password (for simplicity for testing)
     */
    public function definition(): array
    {
        $first_name = fake()->unique()->firstName;
        $surname = fake()->unique()->lastName;
        return [
            'user_name' => $first_name . $surname,
            'first_name' => $first_name,
            'surname' => $surname,
            'email' => fake()->unique()->safeEmail(),
            'user_type' => fake()->randomElement((['student', 'project_supervisor'])), //not userType enum, because it has admin and module_leader
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
