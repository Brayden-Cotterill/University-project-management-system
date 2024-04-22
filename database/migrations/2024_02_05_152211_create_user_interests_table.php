<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */

    /**
     * Creates user_interests table
     * NOTE: composite key is not used due to Eloquent's incompatibility
     * @return void
     */
    public function up(): void
    {
        Schema::create('user_interests', function (Blueprint $table) {
            $table->id()->comment('ID for each user interest because Laravel Eloquent doesnt support composite PKs');
            $table->foreignId('user_id')->comment('Foreign key user_id from users table')->constrained()->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('interest_id')->comment('Foreign key interest id from interests table')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->unique(['user_id', 'interest_id']);//used unique rather than primary due to Laravel

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_interests');
    }
};
