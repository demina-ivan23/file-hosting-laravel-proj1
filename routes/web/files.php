<?php

use App\Models\UserContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Files\FilesController;


Route::get('/', [FilesController::class, 'index'])->name('dashboard');
Route::post('/send/{user}', [FilesController::class, 'store'])->name('store');
Route::get('/show/{file}', [FilesController::class, 'show'])->name('show');
