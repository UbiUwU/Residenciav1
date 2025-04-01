<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\MaestroController;
use App\Http\Controllers\Api\AuthController;

Route::prefix('maestros')->group(function () {
    Route::get('/', [MaestroController::class, 'index']); // Listar todos
    Route::post('/', [MaestroController::class, 'store']); // Crear nuevo
    Route::get('/{id}', [MaestroController::class, 'show']); // Mostrar uno
    Route::put('/{id}', [MaestroController::class, 'update']); // Actualizar
    Route::delete('/{id}', [MaestroController::class, 'destroy']); // Eliminar
});

Route::post('/login', [AuthController::class, 'login']);