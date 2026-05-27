<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class JoinController extends Controller
{
    public function join(Request $request)
    {
        $request->validate([
            'player_name' => 'required|string|max:20',
            'code' => 'required|string|size:4',
        ]);

        $room = Room::where('code', $request->code)->first();

        if (!$room) {
            return back()->withErrors(['code' => 'Room not found.']);
        }

        if ($room->mode === 'race') {
            return app(RaceController::class)->join($request);
        }

        return app(DuelController::class)->join($request);
    }
}
