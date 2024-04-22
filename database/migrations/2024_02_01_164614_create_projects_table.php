<?php
/**
 * project table migration
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id()->comment('Project ID\'s ID. Required because Eloquent doesnt support composite PK\'s');
            $table->foreignId('project_supervisor_id')->comment('Foreign key project supervisor id from project supervisors')->constrained()->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('student_id')->comment('Foreign key student ID from students table')->constrained();

            $table->string('project_name', 100)->nullable(false)->comment('Project name. Can be null as since a project name may not have been created yet');
            $table->unique(['project_supervisor_id', 'student_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
