<?php

use App\Http\Controllers\PositionController;
use App\Http\Controllers\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

$groups_path = base_path('routes/groups') . '/*.php';

foreach( glob($groups_path) as $group ) {
    include $group;
}

Route::get('/positions', [PositionController::class, 'index']);
Route::get('/token', [TokenController::class, 'index']);



