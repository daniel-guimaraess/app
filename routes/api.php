<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\apiProtectedRoute;
use Illuminate\Support\Facades\Route;

#Routes for authentication
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('version', function(){
    return response()->json(['version' => env('APP_VERSION')]);
});


Route::middleware([apiProtectedRoute::class])->group(function () {    
    #Routes for authentication
    Route::post('logout', [AuthController::class, 'logout']);

    #Routes for users
    Route::post('user', [UserController::class, 'create']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::delete('user/{id}', [UserController::class, 'destroy']);

    #Routes for units
    Route::post('unit', [UnitController::class, 'create']);
    Route::put('unit/{id}', [UnitController::class, 'update']);
    Route::get('unit/{id}', [UnitController::class, 'show']);
    Route::delete('unit/{id}', [UnitController::class, 'destroy']);

    #Routes for alerts
    Route::get('alert/{id}', [AlertController::class, 'show']);
    Route::get('countalertstoday', [AlertController::class, 'countAlertsToday']);
    Route::get('lastalerts', [AlertController::class, 'lastAlerts']);
    Route::get('alerts', [AlertController::class, 'index']);
    Route::post('alert', [AlertController::class, 'create']);    

    #Routes for analysis
    Route::get('analyses/{id}', [AnalysisController::class, 'show']);
    Route::get('countanalysestoday', [AnalysisController::class, 'countAnalysesToday']);
    Route::get('analyses', [AnalysisController::class, 'index']);
    Route::post('analyses', [AnalysisController::class, 'create']);    

    #Routes for monitoring
    Route::get('/startmonitoring', [MonitoringController::class, 'startMonitoring']);
    Route::get('/stopmonitoring', [MonitoringController::class, 'stopMonitoring']);
    Route::get('/statusmonitoring', [MonitoringController::class, 'statusMonitoring']);
});