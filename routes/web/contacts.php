<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserContact\UserContactController;
use App\Http\Controllers\Admin\UserContact\UserContactRequests\UserContactRequestController;


Route::get('/', [UserContactController::class, 'index'])->name('dashboard');

Route::get('/create', [UserContactController::class, 'create'])->name('create');
Route::post('/store', [UserContactController::class, 'store'])->name('store');
Route::get('/show/{user}', [UserContactController::class, 'show'])->name('show');

Route::get('/requests', [UserContactRequestController::class, 'index'])->name('requests.dashboard');
Route::get('/requests/create', [UserContactRequestController::class, 'create'])->name('requests.create');
Route::post('/requests/store', [UserContactRequestController::class, 'store'])->name('requests.store');
Route::delete('/requests/delete/{id}/{state}', [UserContactRequestController::class, 'destroy'])->name('requests.delete');
