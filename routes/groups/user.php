<?php
use App\Http\Controllers\UserController;
use App\Http\Middleware\RegisterToken;

Route::controller(UserController::class)->prefix('/users')->group(function() {
    Route::get('/', 'index')->name('users.index');
    Route::get('/{id}', 'getUserById')->name('users.getuserbyid');
    Route::post('/', 'create')->name('users.create')->middleware('register.token');
});