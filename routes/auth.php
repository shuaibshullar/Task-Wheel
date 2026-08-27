<?php

use App\Http\Controllers\changePassword;
use App\Http\Controllers\logout;
use App\Http\Controllers\ViewTaskController;
use Illuminate\Support\Facades\Route;


Route::post('/logout', [logout::class, 'logout'])->name('logout_post');
Route::get("/", [ViewTaskController::class, 'view'])->name('home');

Route::controller(changePassword::class)->group(function () {

    Route::get('/change-password', 'show')->name('password.change');
    Route::post('/change-password', 'changePassword')->name('password.change.post');
});
