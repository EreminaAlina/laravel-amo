<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\LeadsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::get('/signin', [Controller::class, 'signin']);
    Route::get('/signout', [Controller::class, 'signout']);
});


Route::post('/leads', [LeadsController::class, 'change_stage']);
