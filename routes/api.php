<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CookieController;
use App\Http\Controllers\PluploadUploadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('web')->post('/save-canvas-cookie', [CookieController::class, 'store'])->name('save-canvas-cookie');
Route::middleware('web')->post('/update-canvas-cookie', [CookieController::class, 'update'])->name('update-canvas-cookie');
Route::middleware('web')->delete('/delete-plupload-folder/{uuid}', [PluploadUploadController::class, 'destroy'])->name('delete-plupload-folder');
