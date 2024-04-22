<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->nullable(false)->comment('Primary key id. Named ID due to Laravel\'s auto naming scheme.');
            $table->string('user_name', 50)->unique()->nullable(false)->comment('username to login, is unique and not null, with length of 200');
            $table->string('first_name', 100)->nullable(false)->comment('the first name of the user. Not null and unique, with length of 100');
            $table->string('surname', 100)->nullable(false)->comment('surname of user, has not null and unique, with length of 100');
            $table->string('email')->unique()->comment('Email address');
            $table->enum('user_type', ['student', 'project_supervisor', 'module_leader', 'admin'])->default('student')->comment('user type can be either student, project supervisor, module leader or admin, has not null and default is student');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(false)->comment('password, hashed and not null');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
