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
        Schema::create('students', function (Blueprint $table) {
            $table->id()->comment('student ID is unique due AI pk');

            $table->foreignId('user_id')->nullable()->unique()->comment('Foreign user_id from users. Cascades on both update and delete. Nullable due to Filament rules with BelongsTo')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('student_user_name', 50)->unique()->nullable()->comment('Foreign value user_name for students');
            $table->foreign('student_user_name')->references('user_name')->on('users')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
