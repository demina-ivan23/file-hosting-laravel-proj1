<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::get('/home', [UserController::class, 'index'])->name('home');
Route::get('/profile/{user}', [UserController::class, 'show'])->name('profile');
Route::get('/profile/edit/{user}', [UserController::class, 'edit'])->name('edit');
Route::put('profile/update/{user}', [UserController::class, 'update'])->name('update');