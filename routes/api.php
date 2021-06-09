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
    Route::get('/profile/{id}', [\App\Http\Controllers\Api\UserController::class, 'getUserById']);
    Route::post('/logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout']);

    Route::get('/timetable', [\App\Http\Controllers\Api\TimetableController::class, 'getTimetableForWeek']);
    Route::apiResource('timetable', \App\Http\Controllers\Api\TimetableController::class)
        ->only(['show']);

    Route::get('/groups', [\App\Http\Controllers\Api\GroupController::class, 'getGroups']);

    // Rate
    Route::get('/rates', [\App\Http\Controllers\Api\RateController::class, 'getAllStudentRates']);

    Route::group(['middleware' => ['role:admin|educational_part']], function() {
        Route::post('/register', [\App\Http\Controllers\Api\Auth\AuthController::class, 'register']);
        Route::apiResource('timetable', \App\Http\Controllers\Api\TimetableController::class)
            ->only(['store']);
    });

    Route::group(['middleware' => ['role:admin|educational_part|teacher']], function() {
        // Rate
        Route::post('/rates', [\App\Http\Controllers\Api\RateController::class, 'setRateStudent']);
        Route::post('/attendance', [\App\Http\Controllers\Api\RateController::class, 'setAttendanceStudent']);
        Route::get('/lesson/{id}/rates', [\App\Http\Controllers\Api\RateController::class, 'getRatesForGroupByLesson']);

        Route::get('/users', [\App\Http\Controllers\Api\UserController::class, 'getUsersByRole']);
        Route::get('/group', [\App\Http\Controllers\Api\GroupController::class, 'getStudentsByGroup']);

        Route::group(['prefix' => 'admin'], function () {
            Route::apiResource('/users', \App\Http\Controllers\Api\UserController::class);
        });
    });
});





