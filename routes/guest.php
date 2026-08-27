<?php

use App\Http\Controllers\login;
use App\Http\Controllers\passwordResetting;
use App\Http\Controllers\register;
use Illuminate\Support\Facades\Route;


Route::controller(login::class)->group(function() {

    Route::post('/login', 'post_request')->name('login_post');
    Route::get('/login', 'show')->name('login_get');
});


Route::controller(register::class)->group(function() {

    Route::post('/register', 'post_request')->name('register_post');
    Route::get('/register', 'show')->name('register_get');
});


Route::controller(passwordResetting::class)->group(function() {

    Route::get('/forgot-password', 'passwordForgotPage')->name('password-forgot');
    Route::post('/forgot-password', 'passwordForgotPost')->name('password-forgot.post');

    Route::get('/reset-password/{token}/{email}', 'passwordResetPage')
        ->middleware('signed')
        ->name('password.reset');
    Route::post('/reset-password', 'passwordResetPost')->name('password.update');
});
