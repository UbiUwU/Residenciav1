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
use App\Http\Controllers\ProgramaCursoController;
use App\Http\Controllers\PeriodoEscolarController;
use App\Http\Controllers\TipoEventoController;
use App\Http\Controllers\PublicoDestinoController;

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

Route::prefix('periodos-escolares')->group(function () {
    Route::post('/', [PeriodoEscolarController::class, 'store']);
    Route::get('/', [PeriodoEscolarController::class, 'index']);
    Route::get('/{id}', [PeriodoEscolarController::class, 'show']);
    Route::put('/{id}', [PeriodoEscolarController::class, 'update']);
    Route::delete('/{id}', [PeriodoEscolarController::class, 'destroy']);
});

// Rutas para Tipo Evento
Route::prefix('tipos-evento')->group(function () {
    Route::get('/', [TipoEventoController::class, 'index']);
    Route::post('/', [TipoEventoController::class, 'store']);
    Route::get('/{id}', [TipoEventoController::class, 'show']);
    Route::put('/{id}', [TipoEventoController::class, 'update']);
    Route::delete('/{id}', [TipoEventoController::class, 'destroy']);
});

// Rutas para Público Destino
Route::prefix('publicos-destino')->group(function () {
    Route::get('/', [PublicoDestinoController::class, 'index']);
    Route::post('/', [PublicoDestinoController::class, 'store']);
    Route::get('/{id}', [PublicoDestinoController::class, 'show']);
    Route::put('/{id}', [PublicoDestinoController::class, 'update']);
    Route::delete('/{id}', [PublicoDestinoController::class, 'destroy']);
});