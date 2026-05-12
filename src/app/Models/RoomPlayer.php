<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPlayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'player_name',
        'session_id',
        'secret_code',
        'is_host',
        'ready',
        'guesses_count',
        'won_at',
    ];

    protected $casts = [
        'is_host' => 'boolean',
        'ready' => 'boolean',
        'won_at' => 'datetime',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function opponent()
    {
        return $this->room->players()
            ->where('id', '!=', $this->id)
            ->first();
    }

    public function hasWon(): bool
    {
        return $this->won_at !== null;
    }
}
