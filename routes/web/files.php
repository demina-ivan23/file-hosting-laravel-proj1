<?php

use App\Models\UserContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Files\FilesController;
use App\Http\Controllers\Admin\Files\MultipleFilesController;
use App\Http\Controllers\Admin\Files\PersonalFilesController;


Route::get('/', [FilesController::class, 'index'])->name('dashboard');
Route::get('/received', [FilesController::class, 'index'])->name('received');
Route::get('/sent', [FilesController::class, 'index'])->name('sent');

Route::get('/download/{file}', [FilesController::class, 'show'])->name('show');
Route::get('/download/{filePubId}/protected', [FilesController::class, 'show'])->name('pubid.show.protected');

Route::get('/create/{user}', [FilesController::class, 'create'])->name('create');
Route::post('/send/{user}', [FilesController::class, 'store'])->name('store');
Route::delete('/delete/{id}', [FilesController::class, 'destroy'])->name('delete');


Route::post('/multiple/send/{user}', [MultipleFilesController::class, 'store'])->name('multiple.store');
Route::get('/multiple', [MultipleFilesController::class, 'index'])->name('multiple.index');
Route::delete('/multiple/delete', [MultipleFilesController::class, 'destroy'])->name('multiple.delete');


Route::get('/personal/create', [PersonalFilesController::class, 'create'])->name('personal.create');
Route::post('/personal/store', [PersonalFilesController::class, 'store'])->name('personal.store');
Route::get('/personal', [PersonalFilesController::class, 'index'])->name('personal.dashboard');
