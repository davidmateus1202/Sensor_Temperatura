<?php

use App\Http\Controllers\SensorController;
use Illuminate\Support\Facades\Route;

// Rutas de manejo de sensor simulado
Route::post('sensor', [SensorController::class, 'store']);
Route::post('turn/sensor', [SensorController::class, 'switchTurn']);