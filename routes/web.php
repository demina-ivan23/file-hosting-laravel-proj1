<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserContact\UserContactController;
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
Route::prefix('/contact')->middleware('auth')->name('admin.contact.')->group(base_path('routes/web/contact.php'));
Route::prefix('/files')->middleware('auth')->name('admin.files.')->group(base_path('routes/web/files.php'));
