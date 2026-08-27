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
        Schema::create('grand_prix_round_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained('grand_prix_rounds')->cascadeOnDelete();
            $table->foreignId('player_id')->constrained('room_players')->cascadeOnDelete();
            $table->unsignedTinyInteger('guesses_used')->default(0);
            $table->boolean('solved')->default(false);
            $table->unsignedInteger('seconds_taken')->nullable();
            $table->unsignedInteger('score')->default(0);
            $table->timestamp('solved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grand_prix_round_players');
    }
};
