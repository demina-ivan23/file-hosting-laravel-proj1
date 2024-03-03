<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::get('/profile/{user}', [UserController::class, 'show'])->middleware('auth')->name('profile');
Route::get('/profile/edit/{user}', [UserController::class, 'edit'])->middleware('auth')->name('edit');
Route::put('/profile/update/{user}', [UserController::class, 'update'])->middleware('auth')->name('update');
Route::put('/profile/update/reset-public-id/{user}', [UserController::class, 'update'])->middleware('auth')->name('reset_public_id');
