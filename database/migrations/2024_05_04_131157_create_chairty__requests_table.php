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
        Schema::create('chairty__requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('job');
            $table->string('chairty_name');
            $table->string('chairty_address');
            $table->enum('chairty_type', ['medical', 'educational', 'social', 'environmental', 'humanitarian', 'cultural', 'sports', 'economic', 'other']);
            $table->string('financial_license');
            $table->string('ad_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chairty__requests');
    }
};
