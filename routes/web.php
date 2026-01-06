<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'ascii-art' => [
            '    _____   _____  _______ __    _     _______ _     _ _______ _____ _______    ',
            '   |     | |_____] |______ | \  | ___ |  |  | |     | |______   |   |          ',     
            '   |_____| |       |______ |  \_|     |  |  | |_____| ______| __|__ |_____     '
        ],
        'name' => 'open-music-api',
        'version' => '1.0',
        'api-root-url' => url('/api'),
        'documentation' => 'https://',
        'author' => 'TheLastCookie-404',
        'github' => 'https://github.com/TheLastCookie-404',
        'message' => 'press autoformat to get normalized urls!'
    ], 200, [], JSON_PRETTY_PRINT);
});