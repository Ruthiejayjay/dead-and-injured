<?php

use App\Http\Controllers\DuelController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/play', function () {
    return Inertia::render('Play');
});

Route::get('/game/solo', function () {
    return Inertia::render('Game/Solo');
});

Route::get('/multiplayer', function () {
    return Inertia::render('Multiplayer');
});

Route::controller(DuelController::class)->prefix('duel')->name('duel.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/create', 'create')->name('create');
    Route::post('/join', 'join')->name('join');
    Route::get('/room/{code}', 'room')->name('room');
    Route::post('/room/{code}/set-code', 'setCode')->name('set-code');
    Route::post('/room/{code}/guess', 'guess')->name('guess');
    Route::get('/duel/room/{code}/status', 'status')->name('status');
});
