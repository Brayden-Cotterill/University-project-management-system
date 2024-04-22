<?php

/**
 * Interests table migration
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
        Schema::create('interests', function (Blueprint $table) {
            $table->id()->comment('interest ID. Is a auto increment PK');
            $table->string('interest_name', 50)->nullable(false)->unique()->comment('Interest name. Has a max varchar value of 50 as since some names may be long. Also unique and not null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interests');
    }
};
