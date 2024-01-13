<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [MessageController::class, 'index'])->name('dashboard');
Route::post('/store', [MessageController::class, 'store'])->name('store');
Route::delete('/delete/{message}', [MessageController::class, 'destroy'])->name('delete');
