<?php

namespace App\Http\Controllers;

use App\Events\GameFinished;
use App\Events\GameStarted;
use App\Events\GuessMade;
use App\Events\PlayerJoined;
use App\Events\PlayerReady;
use App\Models\Room;
use App\Models\RoomPlayer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DuelController extends Controller
{
    public function index()
    {
        return Inertia::render('Duel/Index');
    }

    public function create(Request $request)
    {
        $request->validate(
            [
                'player_name' => 'required|string|max:25',
            ]
        );

        $room = Room::create([
            'code' => Room::generateCode(),
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

        return redirect()->route('duel.room', $room->code);
    }

    public function join(Request $request)
    {
        $request->validate([
            'player_name' => 'required|string|min:2|max:20',
            'code' => 'required|string|size:6',
        ]);

        $room = Room::where('code', $request->code)->firstOrFail();

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

        broadcast(new PlayerJoined($room, $player))->toOthers();

        $room->update(['status' => 'setting_codes']);

        return redirect()->route('duel.room', $room->code);
    }

    public function room(string $code)
    {
        $room = Room::where('code', $code)
            ->with('players')
            ->firstOrFail();

        $playerId = session('player_id');
        $player = $room->players->firstWhere('id', $playerId);

        if (!$player) {
            return redirect()->route('duel.index');
        }

        $opponent = $room->players->firstWhere('id', '!=', $playerId);

        return Inertia::render('Duel/Room', [
            'room' => [
                'code' => $room->code,
                'status' => $room->status,
            ],
            'player' => [
                'id' => $player->id,
                'name' => $player->player_name,
                'is_host' => $player->is_host,
                'ready' => $player->ready,
            ],
            'opponent' => $opponent ? [
                'id' => $opponent->id,
                'name' => $opponent->player_name,
                'ready' => $opponent->ready,
            ] : null,
        ]);
    }

    public function setCode(Request $request, string $code)
    {
        $request->validate([
            'secret_code' => [
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

        $room = Room::where('code', $code)->firstOrFail();
        $player = RoomPlayer::where('id', session('player_id'))
            ->where('room_id', $room->id)
            ->firstOrFail();

        $player->update([
            'secret_code' => $request->secret_code,
            'ready' => true,
        ]);

        broadcast(new PlayerReady($room, $player))->toOthers();

        $room->refresh();

        if ($room->bothReady()) {
            $room->update([
                'status' => 'playing',
                'started_at' => now(),
            ]);
            broadcast(new GameStarted($room));
        }


        return response()->json(['ready' => true]);
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
            ->where('status', 'playing')
            ->firstOrFail();

        $player = RoomPlayer::where('id', session('player_id'))
            ->where('room_id', $room->id)
            ->firstOrFail();

        $opponent = $player->opponent();
        $secret = $opponent->secret_code;
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
}
