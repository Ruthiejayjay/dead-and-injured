<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/play', function () {
    return Inertia::render('Play');
});

Route::get('/game/new', function () {
    return Inertia::render('Game/New');
});

Route::get('/game/solo', function () {
    return Inertia::render('Game/Solo');
});
