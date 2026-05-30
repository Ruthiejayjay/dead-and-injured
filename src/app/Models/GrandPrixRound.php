<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GrandPrixRound extends Model
{
    protected $fillable = [
        'room_id',
        'round_number',
        'secret_code',
        'status',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function roundPlayers(): HasMany
    {
        return $this->hasMany(GrandPrixRoundPlayer::class, 'round_id');
    }

    public function allPlayersFinished(): bool
    {
        $totalPlayers = $this->room->players()->count();
        $finishedPlayers = $this->roundPlayers()
            ->where(function ($q) {
                $q->where('solved', true)
                    ->orWhereNotNull('seconds_taken');
            })->count();

        return $finishedPlayers >= $totalPlayers;
    }
}
