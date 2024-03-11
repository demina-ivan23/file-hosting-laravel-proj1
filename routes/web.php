<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CookieController;
use App\Http\Controllers\PluploadUploadController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('/contacts')->middleware('auth')->name('admin.contacts.')->group(base_path('routes/web/contacts.php'));

Route::prefix('/files')->middleware('auth')->name('admin.files.')->group(base_path('routes/web/files.php'));

Route::prefix('/messages')->middleware('auth')->name('admin.messages.')->group(base_path('routes/web/messages.php'));

Route::prefix('/global-files')->name('admin.global-files.')->group(base_path('routes/web/global-files.php'));




Route::prefix('/user')->name('user.')->group(base_path('routes/web/user.php'));

// Routes that have no specific prefixing
Route::post('/save-canvas-cookie', [CookieController::class, 'store'])->name('save-canvas-cookie');
Route::post('/update-canvas-cookie', [CookieController::class, 'update'])->name('update-canvas-cookie');
Route::delete('/delete-plupload-folder/{uuid}', [PluploadUploadController::class, 'destroy'])->name('delete-plupload-folder');