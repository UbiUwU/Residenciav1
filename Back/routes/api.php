<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsignaturaController;
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
use App\Http\Controllers\ApiAlumnoller;
use App\Http\Controllers\Api\Aulatroller;
use App\Http\Controllers\Api\BitacoraController;
use App\Http\Controllers\CargaAcademicaController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\ComputadoraController;
use App\Http\Controllers\EdificioController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\Api\ReservacionAlumnoController;
use App\Http\Controllers\Api\ReservacionMaestroController;
use App\Http\Controllers\PlantillaController;
use App\Http\Controllers\Api\AlumnoController;
use App\Http\Controllers\Api\AulaController;
use App\Http\Controllers\EventoCalendarioController;


Route::prefix('maestros')->group(function () {
    Route::get('/', [MaestroController::class, 'index']); // Listar todos
    Route::post('/', [MaestroController::class, 'store']); // Crear nuevo
    Route::get('/{id}', [MaestroController::class, 'show']); // Mostrar uno
    Route::put('/{id}', [MaestroController::class, 'update']); // Actualizar
    Route::delete('/{id}', [MaestroController::class, 'destroy']); // Eliminar
});

Route::prefix('asignaturas')->group(function () {
    Route::get('/', [AsignaturaController::class, 'index']);
    Route::post('/', [AsignaturaController::class, 'store']);
    Route::get('/{clave}', [AsignaturaController::class, 'show']);
    Route::get('/complete/{clave}', [AsignaturaController::class, 'getByClaveComplete']);
    Route::put('/{clave}', [AsignaturaController::class, 'update']);
    Route::delete('/{clave}', [AsignaturaController::class, 'destroy']);
    
    // Rutas adicionales
    Route::get('/carrera/{claveCarrera}', [AsignaturaController::class, 'getByCarrera']);
    Route::get('/carrera/{claveCarrera}/semestre/{semestre}', [AsignaturaController::class, 'getByCarreraAndSemestre']);
});

Route::get('/alumnos', [AlumnoController::class, 'index']);
Route::get('/alumnos/{numeroControl}', [AlumnoController::class, 'show']);
Route::post('/alumnos', [AlumnoController::class, 'store']);
Route::put('/alumnos/{numeroControl}', [AlumnoController::class, 'update']);
Route::delete('/alumnos/{numeroControl}', [AlumnoController::class, 'destroy']);

Route::get('/aulas', [AulaController::class, 'getAllAulas']);
Route::get('/aulas/{claveAula}', [AulaController::class, 'getAulaById']);
Route::post('/aulas', [AulaController::class, 'insertAula']);
Route::put('/aulas/{claveAula}', [AulaController::class, 'updateAula']);
Route::delete('/aulas/{claveAula}', [AulaController::class, 'deleteAula']);

// Bitácora de alumnos
Route::get('bitacora-alumnos', [BitacoraController::class, 'indexAlumnos']);
Route::get('bitacora-alumnos/{numero_control}', [BitacoraController::class, 'showAlumno']);
Route::post('bitacora-alumnos', [BitacoraController::class, 'storeAlumno']);
Route::delete('bitacora-alumnos/{id}', [BitacoraController::class, 'destroyAlumno']);

// Bitácora de maestros
Route::get('bitacora-maestros', [BitacoraController::class, 'indexMaestros']);
Route::get('bitacora-maestros/{tarjeta}', [BitacoraController::class, 'showMaestro']);
Route::post('bitacora-maestros', [BitacoraController::class, 'storeMaestro']);
Route::delete('bitacora-maestros/{id}', [BitacoraController::class, 'destroyMaestro']);

Route::prefix('carga-academica-general')->group(function () {
    Route::get('/', [CargaAcademicaController::class, 'indexGeneral']);
    Route::get('/{id}', [CargaAcademicaController::class, 'showGeneral']);
    Route::post('/', [CargaAcademicaController::class, 'storeGeneral']);
    Route::put('/{id}', [CargaAcademicaController::class, 'updateGeneral']);
    Route::delete('/{id}', [CargaAcademicaController::class, 'destroyGeneral']);
});

Route::prefix('carga-academica-detalle')->group(function () {
    Route::get('/', [CargaAcademicaController::class, 'indexDetalle']);
    Route::get('/{id}', [CargaAcademicaController::class, 'showDetalle']);
    Route::post('/', [CargaAcademicaController::class, 'storeDetalle']);
    Route::put('/{id}', [CargaAcademicaController::class, 'updateDetalle']);
    Route::delete('/{id}', [CargaAcademicaController::class, 'destroyDetalle']);
});

Route::prefix('carreras')->group(function () {
    Route::get('/', [CarreraController::class, 'index']);
    Route::get('/{clave}', [CarreraController::class, 'show']);
    Route::post('/', [CarreraController::class, 'store']);
    Route::put('/{clave}', [CarreraController::class, 'update']);
    Route::delete('/{clave}', [CarreraController::class, 'destroy']);
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
Route::prefix('computadoras')->group(function () {
    Route::get('/', [ComputadoraController::class, 'index']);
    Route::get('/{numero_inventario}', [ComputadoraController::class, 'show']);
    Route::post('/', [ComputadoraController::class, 'store']);
    Route::put('/{numero_inventario}', [ComputadoraController::class, 'update']);
    Route::delete('/{numero_inventario}', [ComputadoraController::class, 'destroy']);
});

Route::prefix('edificios')->group(function () {
    Route::get('/', [EdificioController::class, 'index']);
    Route::get('/{clave_edificio}', [EdificioController::class, 'show']);
    Route::post('/', [EdificioController::class, 'store']);
    Route::put('/{clave_edificio}', [EdificioController::class, 'update']);
    Route::delete('/{clave_edificio}', [EdificioController::class, 'destroy']);
});

Route::prefix('grupos')->group(function () {
    Route::get('/', [GrupoController::class, 'index']);
    Route::get('/{clave_grupo}', [GrupoController::class, 'show']);
    Route::post('/', [GrupoController::class, 'store']);
    Route::put('/{clave_grupo}', [GrupoController::class, 'update']);
    Route::delete('/{clave_grupo}', [GrupoController::class, 'destroy']);
});


Route::prefix('horarios')->group(function () {
    Route::get('/', [HorarioController::class, 'index']);
    Route::get('/{clave_horario}', [HorarioController::class, 'show']);
    Route::post('/', [HorarioController::class, 'store']);
    Route::put('/{clave_horario}', [HorarioController::class, 'update']);
    Route::delete('/{clave_horario}', [HorarioController::class, 'destroy']);
});

Route::prefix('reservaciones')->group(function () {
    Route::get('/', [ReservacionAlumnoController::class, 'index']);      // Listar todas
    Route::post('/', [ReservacionAlumnoController::class, 'store']);     // Crear nueva
    Route::get('/{id}', [ReservacionAlumnoController::class, 'show']);   // Obtener una
    Route::put('/{id}', [ReservacionAlumnoController::class, 'update']); // Actualizar
    Route::delete('/{id}', [ReservacionAlumnoController::class, 'destroy']); // Eliminar
});

Route::prefix('reservaciones-maestro')->group(function () {
    Route::get('/', [ReservacionMaestroController::class, 'index']);       // Listar todas
    Route::post('/', [ReservacionMaestroController::class, 'store']);      // Crear nueva
    Route::get('/{id}', [ReservacionMaestroController::class, 'show']);    // Obtener una
    Route::put('/{id}', [ReservacionMaestroController::class, 'update']);  // Actualizar
    Route::delete('/{id}', [ReservacionMaestroController::class, 'destroy']); // Eliminar
});

Route::prefix('eventos')->group(function () {
    Route::get('/', [EventoCalendarioController::class, 'index']);
    Route::post('/', [EventoCalendarioController::class, 'store']);
    Route::get('/{id}', [EventoCalendarioController::class, 'show']);
    Route::put('/{id}', [EventoCalendarioController::class, 'update']);
    Route::delete('/{id}', [EventoCalendarioController::class, 'destroy']);
});

