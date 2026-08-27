<?php

namespace App\Http\Controllers;

use App\Events\PlayerJoined;
use App\Events\RoundFinished;
use App\Events\RoundStarted;
use App\Events\TournamentFinished;
use App\Http\Requests\CreateRoomRequest;
use App\Models\GrandPrixRound;
use App\Models\GrandPrixRoundPlayer;
use App\Models\Room;
use App\Models\RoomPlayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class GrandPrixController extends Controller
{
    public function index()
    {
        return Inertia::render('GrandPrix/Index');
    }

    public function create(CreateRoomRequest $request)
    {
        $request->validate([
            'max_players' => 'required|integer|min:2|max:10',
            'total_rounds' => 'required|integer|min:1|max:10',
            'max_guesses' => 'required|integer|min:5|max:20',
            'time_limit' => 'required|integer|min:30|max:300',
        ]);

        $room = Room::create([
            'code' => Room::generateCode(),
            'mode' => 'grand_prix',
            'status' => 'waiting',
            'max_players' => $request->max_players,
            'total_rounds' => $request->total_rounds,
            'max_guesses' => $request->max_guesses,
            'time_limit' => $request->time_limit,
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

        return redirect()->route('grand-prix.room', $room->code);
    }

    public function join(Request $request)
    {
        $room = Room::where('code', $request->code)
            ->where('mode', 'grand_prix')
            ->firstOrFail();

        if ($room->isFull()) {
            return back()->withErrors(['code' => 'This room is already full.']);
        }

        if ($room->status !== 'waiting') {
            return back()->withErrors(['code' => 'This tournament has already started.']);
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

        return redirect()->route('grand-prix.room', $room->code);
    }
    public function room(string $code)
    {
        $room = Room::where('code', $code)
            ->where('mode', 'grand_prix')
            ->with('players')
            ->firstOrFail();

        $playerId = session('player_id');
        $player = $room->players->firstWhere('id', $playerId);

        if (!$player) {
            return redirect()->route('grand-prix.index');
        }

        return Inertia::render('GrandPrix/Room', [
            'room' => [
                'code' => $room->code,
                'status' => $room->status,
                'max_players' => $room->max_players,
                'total_rounds' => $room->total_rounds,
                'max_guesses' => $room->max_guesses,
                'time_limit' => $room->time_limit,
                'current_round' => $room->current_round,
            ],
            'player' => [
                'id' => $player->id,
                'name' => $player->player_name,
                'is_host' => $player->is_host,
            ],
            'players' => $room->players->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->player_name,
                'total_score' => $p->total_score,
            ]),
        ]);
    }

    public function start(string $code)
    {
        $room = Room::where('code', $code)
            ->where('mode', 'grand_prix')
            ->firstOrFail();

        $playerId = session('player_id');
        $player = $room->players->firstWhere('id', $playerId);

        if (!$player?->is_host) {
            return response()->json(['error' => 'Only the host can start the tournament.'], 403);
        }

        if ($room->players()->count() < 2) {
            return response()->json(['error' => 'Need at least 2 players to start.'], 422);
        }

        $this->startNextRound($room);

        return response()->json(['started' => true]);
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
            ->where('mode', 'grand_prix')
            ->where('status', 'playing')
            ->firstOrFail();

        $player = RoomPlayer::where('id', session('player_id'))
            ->where('room_id', $room->id)
            ->firstOrFail();

        $round = $room->currentRound();

        if (!$round || $round->status !== 'playing') {
            return response()->json(['error' => 'Round not active.'], 422);
        }

        $roundPlayer = GrandPrixRoundPlayer::firstOrCreate(
            ['round_id' => $round->id, 'player_id' => $player->id],
            ['guesses_used' => 0, 'solved' => false]
        );

        if ($roundPlayer->solved || $roundPlayer->seconds_taken !== null) {
            return response()->json(['error' => 'You have already finished this round.'], 422);
        }

        $secret = $round->secret_code;
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
        $roundPlayer->increment('guesses_used');
        $roundPlayer->refresh();

        $solved = $dead === 4;
        $maxGuessesReached = $roundPlayer->guesses_used >= $room->max_guesses;

        if ($solved) {
            $secondsTaken = min(
                (int) abs(now()->diffInSeconds($round->started_at)),
                $room->time_limit
            );
            $score = GrandPrixRoundPlayer::calculateScore(
                true,
                $roundPlayer->guesses_used,
                $room->max_guesses,
                $secondsTaken,
                $room->time_limit
            );
            $roundPlayer->update([
                'solved' => true,
                'seconds_taken' => $secondsTaken,
                'score' => $score,
                'solved_at' => now(),
            ]);
            $player->increment('total_score', $score);
            $round->refresh();
            if ($round->allPlayersFinished()) {
                $this->endRound($room, $round);
            }
        } elseif ($maxGuessesReached) {
            $roundPlayer->update([
                'solved' => false,
                'seconds_taken' => $room->time_limit,
                'score' => 0,
            ]);
        }

        return response()->json([
            'dead' => $dead,
            'injured' => $injured,
            'solved' => $solved,
            'max_guesses_reached' => $maxGuessesReached,
            'guesses_used' => $roundPlayer->guesses_used,
            'score' => $roundPlayer->score,
        ]);
    }

    public function finishRound(string $code)
    {
        $room = Room::where('code', $code)
            ->where('mode', 'grand_prix')
            ->with('players')
            ->firstOrFail();

        $playerId = session('player_id');
        $player = $room->players->firstWhere('id', $playerId);

        if (!$player?->is_host) {
            return response()->json(['error' => 'Only the host can finish the round.'], 403);
        }

        $round = $room->currentRound();

        if (!$round || $round->status !== 'playing') {
            return response()->json(['error' => 'No active round.'], 422);
        }

        $this->endRound($room, $round);

        return response()->json(['finished' => true]);
    }

    public function status(string $code)
    {
        $room = Room::where('code', $code)
            ->where('mode', 'grand_prix')
            ->with('players')
            ->firstOrFail();

        $round = $room->currentRound();

        $leaderboard = null;
        if ($round && $round->status === 'finished') {
            $leaderboard = [
                'round_number' => $round->round_number,
                'total_rounds' => $room->total_rounds,
                'secret_code' => $round->secret_code,
                'leaderboard' => $this->buildLeaderboard($room, $round),
                'is_last_round' => $round->round_number >= $room->total_rounds,
            ];
        }

        $finalStandings = null;
        if ($room->status === 'finished') {
            $finalStandings = $this->buildFinalStandings($room);
        }

        return response()->json([
            'status' => $room->status,
            'current_round' => $room->current_round,
            'round_status' => $round?->status,
            'round_started_at' => $round?->started_at?->toISOString(),
            'leaderboard' => $leaderboard,
            'final_standings' => $finalStandings,
            'players' => $room->players->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->player_name,
                'is_host' => $p->is_host,
                'total_score' => $p->total_score,
            ]),
        ]);
    }

    private function startNextRound(Room $room)
    {
        $nextRoundNumber = $room->current_round + 1;
        $room->update([
            'status' => 'playing',
            'current_round' => $nextRoundNumber,
        ]);

        $round = GrandPrixRound::create([
            'room_id' => $room->id,
            'round_number' => $nextRoundNumber,
            'secret_code' => $this->generateSecretCode(),
            'status' => 'playing',
            'started_at' => now(),
        ]);

        broadcast(new RoundStarted($room, $round));
    }

    private function endRound(Room $room, GrandPrixRound $round): void
    {
        $round->update(['status' => 'finished', 'finished_at' => now()]);

        $room->players->each(function ($player) use ($round, $room) {
            $existing = GrandPrixRoundPlayer::where('round_id', $round->id)
                ->where('player_id', $player->id)
                ->first();

            if (!$existing) {
                // Player never guessed
                GrandPrixRoundPlayer::create([
                    'round_id' => $round->id,
                    'player_id' => $player->id,
                    'guesses_used' => 0,
                    'solved' => false,
                    'seconds_taken' => $room->time_limit,
                    'score' => 0,
                ]);
            } elseif (!$existing->solved && $existing->seconds_taken === null) {
                // Player guessed but didn't solve and time ran out
                $existing->update([
                    'seconds_taken' => $room->time_limit,
                    'score' => 0,
                ]);
            }
        });

        $leaderboard = $this->buildLeaderboard($room, $round);
        $isLastRound = $round->round_number >= $room->total_rounds;

        broadcast(new RoundFinished($room, $round, $leaderboard));

        if ($isLastRound) {
            $room->update(['status' => 'finished', 'finished_at' => now()]);
            $finalStandings = $this->buildFinalStandings($room);
            broadcast(new TournamentFinished($room, $finalStandings));
        }
    }

    private function buildLeaderboard(Room $room, GrandPrixRound $round): array
    {
        $round->load('roundPlayers.player');
        return $room->players->map(function ($player) use ($round) {
            $roundPlayer = $round->roundPlayers
                ->firstWhere('player_id', $player->id);

            return [
                'player_id' => $player->id,
                'name' => $player->player_name,
                'solved' => $roundPlayer?->solved ?? false,
                'guesses_used' => $roundPlayer?->guesses_used ?? 0,
                'seconds_taken' => $roundPlayer?->seconds_taken ?? null,
                'round_score' => $roundPlayer?->score ?? 0,
                'total_score' => $player->fresh()->total_score,
            ];
        })
            ->sortByDesc('total_score')
            ->values()
            ->toArray();
    }

    private function buildFinalStandings(Room $room): array
    {
        return $room->players()
            ->orderByDesc('total_score')
            ->get()
            ->map(fn($p) => [
                'player_id' => $p->id,
                'name' => $p->player_name,
                'total_score' => $p->total_score,
            ])
            ->toArray();
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
