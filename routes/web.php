<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('default');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
