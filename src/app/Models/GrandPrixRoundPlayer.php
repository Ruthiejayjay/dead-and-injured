<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrandPrixRoundPlayer extends Model
{
    protected $fillable = [
        'round_id',
        'player_id',
        'guesses_used',
        'solved',
        'seconds_taken',
        'score',
        'solved_at',
    ];

    protected $casts = [
        'solved' => 'boolean',
        'solved_at' => 'datetime',
    ];

    public function round(): BelongsTo
    {
        return $this->belongsTo(GrandPrixRound::class, 'round_id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(RoomPlayer::class, 'player_id');
    }

    public static function calculateScore(
        bool $solved,
        int $guessesUsed,
        int $maxGuesses,
        int $secondsTaken,
        int $timeLimit
    ): int {
        if (!$solved) return 0;

        $base = 100;
        $guessBonus = max(0, ($maxGuesses - $guessesUsed) * 5);
        $timeBonus = (int) max(0, (($timeLimit - $secondsTaken) / $timeLimit) * 50);

        return $base + $guessBonus + $timeBonus;
    }
}
