<?php

use App\Models\UserContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserContact\UserContactController;
use App\Http\Controllers\Admin\UserContact\Chat\UserContactChatController;


Route::get('/show/{user}', [UserContactChatController::class, 'show'])->name('start-chat');
Route::post('/send/{user}', [UserContactChatController::class, 'store'])->name('store');