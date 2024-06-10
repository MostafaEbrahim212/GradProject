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
        Schema::create('recommendation_tables', function (Blueprint $table) {
            $table->id();
            $table->integer('age');
            $table->enum('education_level', ['bachelor', 'master', 'phd']);
            $table->enum('previous_donation_type', ['food', 'books', 'electronics', 'clothes']);
            $table->boolean('previous_volunteeer');
            $table->enum('personal_interests', ['sports', 'health', 'education', 'agriculture', 'environment']);
            $table->enum('profession', ['engineer', 'doctor', 'teacher', 'lawyer', 'scientist', 'artist']);
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendation_tables');
    }
};
