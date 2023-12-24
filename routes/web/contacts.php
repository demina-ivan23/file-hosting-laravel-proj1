<?php

use App\Models\UserContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserContact\UserContactController;


Route::get('/', [UserContactController::class, 'index'])->name('dashboard');

Route::get('/create', [UserContactController::class, 'create'])->name('create');
Route::post('/store', [UserContactController::class, 'store'])->name('store');