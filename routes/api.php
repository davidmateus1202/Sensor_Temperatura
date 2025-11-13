<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SensorController;
use Illuminate\Support\Facades\Route;

// Rutas de manejo de sensor simulado
Route::post('sensor', [SensorController::class, 'store']);
Route::post('turn/sensor', [SensorController::class, 'switchTurn'])->middleware('auth:sanctum');
Route::get('sumary', [SensorController::class, 'getDataSumary'])->middleware('auth:sanctum');

//auth
Route::post('login', [AuthController::class, 'login']);
Route::get('verify-token', [AuthController::class, 'verifyToken'])->middleware('auth:sanctum');