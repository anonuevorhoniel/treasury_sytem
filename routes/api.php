<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\PayableController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    Route::post('/authenticate', 'authenticate');
    Route::get('/logout', 'logout');
});
Route::middleware("auth:sanctum")->group(function () {
    Route::controller(PayableController::class)->prefix('/payables')->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store');
        Route::post('/print', 'print');
        Route::get('/{payable}/edit', 'edit');
        Route::post('/{payable}/update', 'update');
    });

    Route::controller(DashboardController::class)->prefix('/dashboard')->group(function () {
        Route::get('/', 'index');
    });

    Route::controller(OfficeController::class)->prefix('/offices')->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store');
    });

    Route::controller(UserController::class)->prefix('/users')->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store');
    });

    Route::controller(ActivityLogController::class)->prefix('/activity-logs')->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store');
    });
});
