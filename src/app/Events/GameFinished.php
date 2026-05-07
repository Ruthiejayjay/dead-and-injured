<?php

namespace App\Events;

use App\Models\Room;
use App\Models\RoomPlayer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameFinished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Room $room,
        public RoomPlayer $winner
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
            'winner_id' => $this->winner->id,
            'winner_name' => $this->winner->player_name,
            'guesses_count' => $this->winner->guesses_count,
            'loser_name' => $this->room->players
                ->firstWhere('id', '!=', $this->winner->id)?->player_name,
        ];
    }
}
