<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'mode',
        'status',
        'secret_code',
        'max_players',
        'total_rounds',
        'max_guesses',
        'time_limit',
        'current_round',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'max_players' => 'integer',
        'total_rounds' => 'integer',
        'max_guesses' => 'integer',
        'time_limit' => 'integer',
        'current_round' => 'integer',
    ];

    public function players(): HasMany
    {
        return $this->hasMany(RoomPlayer::class);
    }

    public function host(): HasOne
    {
        return $this->hasOne(RoomPlayer::class)->where('is_host', true);
    }

    public function opponent(): HasOne
    {
        return $this->hasOne(RoomPlayer::class)->where('is_host', false);
    }

    public function bothReady(): bool
    {
        return $this->players()->count() === 2
            && $this->players()->where('ready', false)->count() === 0;
    }

    public static function generateCode(): string
    {
        do {
            $code = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function grandPrixRounds(): HasMany
    {
        return $this->hasMany(GrandPrixRound::class);
    }

    public function currentRound()
    {
        return $this->grandPrixRounds()
            ->where('round_number', $this->current_round)
            ->first();
    }

    public function isFull(): bool
    {
        return $this->players()->count() >= $this->max_players;
    }
}
