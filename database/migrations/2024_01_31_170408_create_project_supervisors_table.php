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
        Schema::create('project_supervisors', function (Blueprint $table) {
            $table->id()->comment('Project supervisor ID for project supervisors');

            $table->foreignId('user_id')->nullable()->unique()->comment('Foreign key user_id from users. Nullable due to how Filament works with BelongsTo')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->tinyInteger('max_student_assign')->nullable(false)->default(3)->comment('The max number of students this supervisor will do. Default is 3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_supervisors');
    }
};
