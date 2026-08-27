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
        Schema::table('rooms', function (Blueprint $table) {
            $table->unsignedTinyInteger('max_players')->default(2)->after('mode');
            $table->unsignedTinyInteger('total_rounds')->default(5)->after('max_players');
            $table->unsignedTinyInteger('max_guesses')->default(15)->after('total_rounds');
            $table->unsignedTinyInteger('time_limit')->default(120)->after('max_guesses');
            $table->unsignedTinyInteger('current_round')->default(0)->after('time_limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['max_players', 'total_rounds', 'max_guesses', 'time_limit', 'current_round']);
        });
    }
};
