<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MultipleMessageController;

Route::get('/', [MessageController::class, 'index'])->name('dashboard');
Route::post('/store', [MessageController::class, 'store'])->name('store');
Route::delete('/delete/{message}', [MessageController::class, 'destroy'])->name('delete');

// Multiple messages route is basically used for choosing multiple
// messages to delete
Route::get('/multiple', [MultipleMessageController::class, 'index'])->name('multiple.index');
Route::delete('/multiple/delete', [MultipleMessageController::class, 'destroy'])->name('multiple.delete');
