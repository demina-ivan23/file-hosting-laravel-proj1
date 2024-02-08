<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::get('/home', [UserController::class, 'index'])->name('home');
Route::get('/profile/{user}', [UserController::class, 'show'])->name('profile');
