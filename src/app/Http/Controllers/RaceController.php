<?php

namespace App\Http\Controllers;

use App\Events\GameFinished;
use App\Events\GameStarted;
use App\Events\GuessMade;
use App\Events\PlayerJoined;
use App\Http\Requests\CreateRoomRequest;
use App\Http\Requests\JoinRoomRequest;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomPlayer;
use Inertia\Inertia;

class RaceController extends Controller
{
    public function index()
    {
        return Inertia::render('Race/Index');
    }

    public function create(CreateRoomRequest $request)
    {
        $room = Room::create([
            'code' => Room::generateCode(),
            'mode' => 'race',
            'status' => 'waiting',
        ]);

        $player = RoomPlayer::create([
            'room_id' => $room->id,
            'player_name' => $request->player_name,
            'session_id' => session()->getId(),
            'is_host' => true,
        ]);

        session([
            'room_code' => $room->code,
            'player_id' => $player->id,
        ]);

        return redirect()->route('race.room', $room->code);
    }

    public function join(JoinRoomRequest $request)
    {
        $room = Room::where('code', $request->code)
            ->where('mode', 'race')
            ->firstOrFail();

        if ($room->isFull()) {
            return back()->withErrors(['code' => 'This room is already full.']);
        }

        if ($room->status !== 'waiting') {
            return back()->withErrors(['code' => 'This game has already started.']);
        }

        $player = RoomPlayer::create([
            'room_id' => $room->id,
            'player_name' => $request->player_name,
            'session_id' => session()->getId(),
            'is_host' => false,
        ]);

        session([
            'room_code' => $room->code,
            'player_id' => $player->id,
        ]);

        $secretCode = $this->generateSecretCode();

        $room->update([
            'status' => 'playing',
            'secret_code' => $secretCode,
            'started_at' => now(),
        ]);

        broadcast(new PlayerJoined($room, $player))->toOthers();
        broadcast(new GameStarted($room));

        return redirect()->route('race.room', $room->code);
    }

    public function room(string $code)
    {
        $room = Room::where('code', $code)
            ->where('mode', 'race')
            ->with('players')
            ->firstOrFail();

        $playerId = session('player_id');
        $player = $room->players->firstWhere('id', $playerId);

        if (!$player) {
            return redirect()->route('race.index');
        }

        $opponent = $room->players->firstWhere('id', '!=', $playerId);

        return Inertia::render('Race/Room', [
            'room' => [
                'code' => $room->code,
                'status' => $room->status,
            ],
            'player' => [
                'id' => $player->id,
                'name' => $player->player_name,
                'is_host' => $player->is_host,
            ],
            'opponent' => $opponent ? [
                'id' => $opponent->id,
                'name' => $opponent->player_name,
            ] : null,
        ]);
    }

    public function guess(Request $request, string $code)
    {
        $request->validate([
            'guess' => [
                'required',
                'string',
                'size:4',
                'regex:/^\d{4}$/',
                function ($attribute, $value, $fail) {
                    if (count(array_unique(str_split($value))) !== 4) {
                        $fail('All 4 digits must be different.');
                    }
                },
            ],
        ]);

        $room = Room::where('code', $code)
            ->where('mode', 'race')
            ->where('status', 'playing')
            ->firstOrFail();

        $player = RoomPlayer::where('id', session('player_id'))
            ->where('room_id', $room->id)
            ->firstOrFail();

        $secret = $room->secret_code;
        $guess = $request->guess;

        $dead = 0;
        $injured = 0;
        for ($i = 0; $i < 4; $i++) {
            if ($guess[$i] === $secret[$i]) {
                $dead++;
            } elseif (str_contains($secret, $guess[$i])) {
                $injured++;
            }
        }

        $won = $dead === 4;

        $player->increment('guesses_count');

        broadcast(new GuessMade($room, $player, $player->guesses_count))->toOthers();

        if ($won) {
            $player->update(['won_at' => now()]);
            $room->update(['status' => 'finished', 'finished_at' => now()]);
            broadcast(new GameFinished($room, $player));
        }

        return response()->json([
            'dead' => $dead,
            'injured' => $injured,
            'won' => $won,
            'guesses_count' => $player->guesses_count,
        ]);
    }

    public function status(string $code)
    {
        $room = Room::where('code', $code)
            ->where('mode', 'race')
            ->with('players')
            ->firstOrFail();

        $playerId = session('player_id');
        $opponent = $room->players->firstWhere('id', '!=', $playerId);
        $winner = $room->players->firstWhere('won_at', '!=', null);

        return response()->json([
            'status' => $room->status,
            'opponent_guesses' => $opponent?->guesses_count,
            'winner_id' => $winner?->id,
            'winner_name' => $winner?->player_name,
            'winner_guesses' => $winner?->guesses_count,
            'opponent' => $opponent ? [
                'id' => $opponent->id,
                'name' => $opponent->player_name,
            ] : null,
        ]);
    }


    private function generateSecretCode(): string
    {
        $digits = [];
        while (count($digits) < 4) {
            $d = (string) random_int(0, 9);
            if (!in_array($d, $digits)) {
                $digits[] = $d;
            }
        }
        return implode('', $digits);
    }
}
