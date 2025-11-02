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
        Schema::table('habits_logs', function (Blueprint $table) {
            $table->integer('duration')->default(0)->after('date'); // Duration in seconds
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('habits_logs', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
};
