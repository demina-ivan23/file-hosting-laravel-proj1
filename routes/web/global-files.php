<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Files\FilesController;
use App\Http\Controllers\Admin\GlobalFiles\GlobalFileController;
use App\Http\Controllers\CommentController;

Route::get('/public', [GlobalFileController::class, 'index'])->name('public');

Route::get('/protected', [GlobalFileController::class, 'index'])->middleware('auth')->name('protected');

Route::get('/show/{file}', [GlobalFileController::class, 'show'])->name('show');
Route::get('/download/{filePubId}/public', [FilesController::class, 'show'])->name('pubid.show.public');
Route::post('/like/{file}', [GlobalFileController::class, 'show'])->middleware('auth')->name('show.like');


Route::get('/public/create', [GlobalFileController::class, 'create'])->middleware('auth')->name('public.create');
Route::get('/protected/create', [GlobalFileController::class, 'create'])->middleware('auth')->name('protected.create');

Route::post('/store', [GlobalFileController::class, 'store'])->middleware('auth')->name('store');

Route::delete('/delete/{id}', [GlobalFileController::class, 'destroy'])->middleware('auth')->name('delete');

Route::post('/{file}/comment/store', [CommentController::class, 'store'])->middleware('auth')->name('comments.store');
Route::post('/comments/{comment}/like', [CommentController::class, 'show'])->middleware('auth')->name('comments.show.like');
Route::delete('/comments/delete/{id}', [CommentController::class, 'destroy'])->middleware('auth')->name('comments.delete');
