<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\registerAdmin;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


Route::controller(TaskController::class)->prefix('task')->group(function () {

    Route::post('/add_and_edit', 'add_and_edit')->name('add_and_edit_task');
    Route::post('/delete','del')->name('delete_task');
});


Route::controller(CategoryController::class)->prefix('cat')->group(function () {

    Route::post('/add_and_edit', 'add_and_edit')->name('add_and_edit_cat');
    Route::post('/delete','del')->name('delete_cat');
});

Route::controller(registerAdmin::class)->group(function () {

    Route::get('/register-admin', 'register')->name('register.admin');
    Route::post('/register-admin', 'registerPost')->name('register.admin.post');
});
