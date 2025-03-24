<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\DasborController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Auth::routes([
    'register' => false,
    'verify' => false,
]);

Route::middleware(['auth', 'password.weak'])->group(function () {
    Route::get('/', [DasborController::class, 'index'])->name('dasbor');
    Route::get('password.change', [ChangePasswordController::class, 'showResetForm'])->name('password.change');
    Route::post('password.change', [ChangePasswordController::class, 'reset'])->name('password.change-post');
    Route::get('token', TokenController::class)->name('token');
    Route::resource('users', UserController::class)->except(['show'])->middleware('easyauthorize:user');
    Route::resource('roles', RoleController::class)->except(['show'])->middleware('easyauthorize:role');
});
