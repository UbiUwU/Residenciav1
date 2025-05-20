<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AsignaturaController;
use App\Http\Controllers\Api\MaestroController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\HorarioMaestroController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RoleController;

use App\Http\Controllers\PlantillaController;

Route::prefix('maestros')->group(function () {
    Route::get('/', [MaestroController::class, 'index']); // Listar todos
    Route::post('/', [MaestroController::class, 'store']); // Crear nuevo
    Route::get('/{id}', [MaestroController::class, 'show']); // Mostrar uno
    Route::put('/{id}', [MaestroController::class, 'update']); // Actualizar
    Route::delete('/{id}', [MaestroController::class, 'destroy']); // Eliminar
});

Route::prefix('asignaturas')->group(function () {
    Route::get('/', [AsignaturaController::class, 'index']); // Todas las asignaturas
    Route::get('/{clave}', [AsignaturaController::class, 'show']); // Una asignatura por clave
});

Route::get('/plantillas', [PlantillaController::class, 'index']);
Route::get('/plantillas/{id}', [PlantillaController::class, 'show']);
Route::post('/plantillas', [PlantillaController::class, 'store']);

Route::post('/login', [AuthController::class, 'login']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum'); // O usa tu middleware de autenticación

Route::get('/horarios/{maestro_id}', [HorarioMaestroController::class, 'index']);
Route::post('/horarios', [HorarioMaestroController::class, 'store']);
Route::put('/horarios/{id}', [HorarioMaestroController::class, 'update']);
Route::delete('/horarios/{id}', [HorarioMaestroController::class, 'destroy']);

Route::prefix('departamentos')->group(function () {
    Route::get('/', [DepartamentoController::class, 'index']);
    Route::get('/{id}', [DepartamentoController::class, 'show']);
    Route::post('/', [DepartamentoController::class, 'store']);
    Route::put('/{id}', [DepartamentoController::class, 'update']);
    Route::delete('/{id}', [DepartamentoController::class, 'destroy']);
});

Route::get('/usuarios', [UsuarioController::class, 'getAll']);
Route::get('/usuarios/{id}', [UsuarioController::class, 'getById']);
Route::post('/usuarios', [UsuarioController::class, 'insert']);
Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
Route::delete('/usuarios/{id}', [UsuarioController::class, 'delete']);

Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles/{id}', [RoleController::class, 'show']);
Route::post('/roles', [RoleController::class, 'store']);
Route::put('/roles/{id}', [RoleController::class, 'update']);
Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
