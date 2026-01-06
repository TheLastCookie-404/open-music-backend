<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'open-music-api' => [
            '    _____   _____  _______ __    _     _______ _     _ _______ _____ _______   ',
            '   |     | |_____] |______ | \  | ___ |  |  | |     | |______   |   |          ',     
            '   |_____| |       |______ |  \_|     |  |  | |_____| ______| __|__ |_____     '
        ],
        'version' => '1.0',
        'documentation' => 'https://',
        'author' => 'TheLastCookie-404',
        'github' => 'https://github.com/TheLastCookie-404',
        'message' => 'press autoformat to get normalized urls!'
    ], 200, [], JSON_PRETTY_PRINT);
});