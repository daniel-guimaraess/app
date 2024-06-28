<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\apiProtectedRoute;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware([apiProtectedRoute::class])->group(function () {
    

});