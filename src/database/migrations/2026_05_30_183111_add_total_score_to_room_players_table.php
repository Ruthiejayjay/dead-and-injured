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
        Schema::table('room_players', function (Blueprint $table) {
            $table->unsignedInteger('total_score')->default(0)->after('guesses_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_players', function (Blueprint $table) {
            $table->dropColumn('total_score');
        });
    }
};
