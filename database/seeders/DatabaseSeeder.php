<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Project;
use App\Models\ProjectSupervisor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @run returns:
     * the user factory of create:
     * A test admin user with email: admin@bbk.ac.uk
     * A test module leader with email :moduleleader@bbk.ac.uk
     * 20 different users, being either student or project supervisor
     */
    public function run(): void
    {
        User::factory(20)->create();


        DB::table('users')->insert([
            ['user_name' => 'Admin', 'first_name' => 'Admin',
                'surname' => 'User', 'email' => 'admin@bbk.ac.uk',
                'user_type' => 'admin', 'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ],
            ['user_name' => 'ModuleLeader', 'first_name' => 'Module',
                'surname' => 'Leader', 'email' => 'moduleleader@bbk.ac.uk',
                'user_type' => 'module_leader', 'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ],
            ['user_name' => 'TestStudent', 'first_name' => 'Test',
                'surname' => 'Student', 'email' => 'TestStudent@bbk.ac.uk',
                'user_type' => 'student', 'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);


        /**
         * Insert Interest tags
         * Note: these are example interest tags
         */

        DB::table('interests')->insert([
            ['interest_name' => 'AI & Machine Learning',
                'created_at' => now(),
                'updated_at' => now()],
            ['interest_name' => 'Data Science',
                'created_at' => now(),
                'updated_at' => now()],
            ['interest_name' => 'Database Systems',
                'created_at' => now(),
                'updated_at' => now()],
            ['interest_name' => 'Information Security',
                'created_at' => now(),
                'updated_at' => now()],
            ['interest_name' => 'E-commerce',
                'created_at' => now(),
                'updated_at' => now()],
        ]);

        /**
         * Insert user_id's into project supervisors and students
         * Technically I could do where id = id
         */

        DB::table('project_supervisors')->insertUsing([
            'user_id', 'created_at', 'updated_at'
        ], DB::table('users')->select(
            'id', 'created_at', 'updated_at'
        )->where('user_type', '=', 'project_supervisor'));

        DB::table('students')->insertUsing([
            'user_id', 'student_user_name', 'created_at', 'updated_at'
        ], DB::table('users')->select(
            'id', 'user_name', 'created_at', 'updated_at'
        )->where('user_type', '=', 'student'));

        DB::table('admins')->insertUsing([
            'user_id', 'created_at', 'updated_at'
        ], DB::table('users')->select(
            'id', 'created_at', 'updated_at'
        )->where('user_type', '=', 'admin'));

        DB::table('module_leaders')->insertUsing([
            'user_id', 'created_at', 'updated_at'
        ], DB::table('users')->select(
            'id', 'created_at', 'updated_at'
        )->where('user_type', '=', 'module_leader'));

        for ($i = 1; $i <= 5; $i++) {
            Project::factory(rand(1, 3))->for(ProjectSupervisor::factory())->has(Student::factory())->create();
        }

    }
}
