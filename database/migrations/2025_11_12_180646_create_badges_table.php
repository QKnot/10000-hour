<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->string('id', 12)->primary();
            $table->string('name');
            $table->text('description');
            $table->string('icon')->nullable(); // Icon class or emoji
            $table->string('badge_type'); // 'hours', 'streak', 'daily_goal', etc.
            $table->integer('requirement_value')->nullable(); // e.g., 100 hours, 7 days streak
            $table->string('color')->default('#667eea'); // Badge color
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
