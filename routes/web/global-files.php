<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Files\FilesController;
use App\Http\Controllers\Admin\GlobalFiles\GlobalFileController;

Route::get('/public', [GlobalFileController::class, 'index'])->name('public');
Route::get('/protected', [GlobalFileController::class, 'index'])->name('protected');
Route::get('/show/{file}', [GlobalFileController::class, 'show'])->name('show');

Route::get('/download/{filePubId}/public', [FilesController::class, 'show'])->name('pubid.show.public');

Route::get('/public/create', [GlobalFileController::class, 'create'])->middleware('auth')->name('public.create');
Route::get('/protected/create', [GlobalFileController::class, 'create'])->middleware('auth')->name('protected.create');

Route::post('/store', [GlobalFileController::class, 'store'])->middleware('auth')->name('store');

Route::delete('/delete/{id}', [GlobalFileController::class, 'destroy'])->middleware('auth')->name('delete');
