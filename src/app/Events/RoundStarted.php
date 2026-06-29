<?php

namespace App\Events;

use App\Models\GrandPrixRound;
use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoundStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Room $room,
        public GrandPrixRound $round,
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('room.' . $this->room->code);
    }

    public function broadcastWith(): array
    {
        return [
            'round_number' => $this->round->round_number,
            'total_rounds' => $this->room->total_rounds,
            'time_limit' => $this->room->time_limit,
            'max_guesses' => $this->room->max_guesses,
            'started_at' => $this->round->started_at->toISOString(),
        ];
    }
}
