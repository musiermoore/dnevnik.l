<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/login', [\App\Http\Controllers\Api\Auth\AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', [\App\Http\Controllers\Api\UserController::class, 'getUser']);
    Route::post('/logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout']);

    Route::get('/timetables', [\App\Http\Controllers\Api\TimetableController::class, 'getTimetableForWeek']);

    Route::group(['middleware' => ['role:admin|educational_part']], function() {
        Route::post('/register', [\App\Http\Controllers\Api\Auth\AuthController::class, 'register']);
    });

    Route::group(['middleware' => ['role:admin|educational_part|teacher']], function() {
        Route::get('/users', [\App\Http\Controllers\Api\UserController::class, 'getUsersByRole']);
        Route::get('/group', [\App\Http\Controllers\Api\GroupController::class, 'getStudentsByGroup']);
    });
});





