<?php

use App\Http\Controllers\ComisionController;
use App\Http\Controllers\CompetenciaController;
use App\Http\Controllers\PracticaController;
use App\Http\Controllers\ProyectoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\Api\MaestroController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PeriodoEscolarController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\ComputadoraController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\Api\AulaController;
use App\Http\Controllers\PresentacionController;
use App\Http\Controllers\DisenoController;
use App\Http\Controllers\AvanceController;

Route::get('avance', [AvanceController::class, 'obtenerAvancesCompletos']);
Route::post('/avance', [AvanceController::class, 'crear']);
Route::put('/avance/{id}', [AvanceController::class, 'actualizarAvance']);



Route::prefix('maestros')->group(function () {
    Route::get('/', [MaestroController::class, 'index']); // Listar todos
    Route::post('/', [MaestroController::class, 'store']); // Crear nuevo
    Route::get('/{id}', [MaestroController::class, 'show']); // Mostrar uno
    Route::put('/{id}', [MaestroController::class, 'update']); // Actualizar
    Route::delete('/{id}', [MaestroController::class, 'destroy']); // Eliminar
    Route::get('/ListaM/{tarjeta}', [MaestroController::class, 'ListaM']);
});

Route::get('/aulas', [AulaController::class, 'getAllAulas']);
Route::get('/aulas/{claveAula}', [AulaController::class, 'getAulaById']);
Route::post('/aulas', [AulaController::class, 'insertAula']);
Route::put('/aulas/{claveAula}', [AulaController::class, 'updateAula']);
Route::delete('/aulas/{claveAula}', [AulaController::class, 'deleteAula']);


Route::prefix('carreras')->group(function () {
    Route::get('/', [CarreraController::class, 'index']);
    Route::get('/{clave}', [CarreraController::class, 'show']);
    Route::post('/', [CarreraController::class, 'store']);
    Route::put('/{clave}', [CarreraController::class, 'update']);
    Route::delete('/{clave}', [CarreraController::class, 'destroy']);
});

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

Route::prefix('computadoras')->group(function () {
    Route::get('/', [ComputadoraController::class, 'index']);
    Route::get('/{numero_inventario}', [ComputadoraController::class, 'show']);
    Route::post('/', [ComputadoraController::class, 'store']);
    Route::put('/{numero_inventario}', [ComputadoraController::class, 'update']);
    Route::delete('/{numero_inventario}', [ComputadoraController::class, 'destroy']);
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

    //Rutas para los datos generales de la materia
Route::prefix('asignaturas')->group(function () {
    Route::get('/', [AsignaturaController::class, 'index']);
    Route::post('/', [AsignaturaController::class, 'store']);
    Route::get('/{clave}', [AsignaturaController::class, 'show']);
    Route::put('/{clave}', [AsignaturaController::class, 'update']);
    Route::delete('/{clave}', [AsignaturaController::class, 'destroy']);
    Route::get('/maestro/{clave}', [AsignaturaController::class, 'getByTarjetaComplete']);
    Route::get('/grupos/{clave}', [AsignaturaController::class, 'getDetalleGruposByTarjeta']);
    Route::get('/complete/{clave}', [AsignaturaController::class, 'getByClaveComplete']);
    //Reporte
    Route::get('/asignaturas/generate-pdf', [AsignaturaController::class, 'generatePDF']);
   
});

Route::prefix('presentacion')->group(function () {
    Route::post('/', [PresentacionController::class, 'store']);
    Route::put('/{id}', [PresentacionController::class, 'update']);
    Route::delete('/{id}', [PresentacionController::class, 'destroy']);
});

Route::post('/diseno', [DisenoController::class, 'store']);
Route::put('/diseno/{id}', [DisenoController::class, 'update']);
Route::delete('/diseno/{id}', [DisenoController::class, 'destroy']);
Route::put('/diseno/participantes/{id}', [DisenoController::class, 'updateParticipantes']);
Route::delete('/diseno/{id}/participante/{participante_id}', [DisenoController::class, 'eliminarParticipante']);

Route::post('/competencias', [CompetenciaController::class, 'store']);
Route::put('/competencias/{id}', [CompetenciaController::class, 'update']);
Route::delete('/competencias/{id}', [CompetenciaController::class, 'destroy']);

Route::post('/practicas', [PracticaController::class, 'store']);
Route::put('/practicas/{id}', [PracticaController::class, 'update']);
Route::delete('/practicas/{id}', [PracticaController::class, 'destroy']);

Route::post('/proyecto', [ProyectoController::class, 'store']);
Route::put('/proyecto/{id}', [ProyectoController::class, 'update']);
Route::delete('/proyecto/{id}', [ProyectoController::class, 'destroy']);

use App\Http\Controllers\EvaluacionController;

Route::prefix('evaluacion')->group(function () {
    Route::post('/', [EvaluacionController::class, 'store']);
    Route::put('/{id}', [EvaluacionController::class, 'update']);
    Route::delete('/{id}', [EvaluacionController::class, 'destroy']);
});

use App\Http\Controllers\FuenteInformacionController;

Route::prefix('fuente')->group(function () {
    Route::post('/', [FuenteInformacionController::class, 'store']);
    Route::put('/{id}', [FuenteInformacionController::class, 'update']);
    Route::delete('/{id}', [FuenteInformacionController::class, 'destroy']);

});

use App\Http\Controllers\TemaController;

Route::post('/tema', [TemaController::class, 'store']);
Route::put('/tema/{id}', [TemaController::class, 'update']);
Route::delete('/tema/{id}', [TemaController::class, 'destroy']);
Route::get('/tema', [TemaController::class, 'index']);
Route::get('/tema/{id}', [TemaController::class, 'show']);
Route::get('/temaSub/{claveAsignatura}', [TemaController::class, 'obtenerTemasYSubtemasPorAsignatura']);

use App\Http\Controllers\SubtemaController;

Route::post('/subtema', [SubtemaController::class, 'store']);
Route::put('/subtema/{id}', [SubtemaController::class, 'update']);
Route::delete('/subtema/{id}', [SubtemaController::class, 'destroy']);

use App\Http\Controllers\CompetenciaTemaController;

Route::prefix('actividades')->group(function () {
    Route::post('/', [CompetenciaTemaController::class, 'store']);
    Route::put('/', [CompetenciaTemaController::class, 'update']);
    Route::delete('/{id}', [CompetenciaTemaController::class, 'destroy']);

});


use App\Http\Controllers\CalificacionUnidadController;

Route::post('/calificaciones', [CalificacionUnidadController::class, 'store']);
Route::get('/calificaciones/reporte/{tarjeta}', [CalificacionUnidadController::class, 'getDetalleGruposPorCarrera']);

Route::post('/comisiones', [ComisionController::class, 'store']);
Route::put('/comisiones/{id}', [ComisionController::class, 'update']);
Route::delete('/comisiones/{id}', [ComisionController::class, 'destroy']);
Route::get('/comisiones', [ComisionController::class, 'index']);
Route::get('/comisiones/maestro/{tarjeta}', [ComisionController::class, 'getByMaestro']);

//Esta zona es de la zona movil.
use App\Http\Controllers\Api\MaestroMController;

Route::prefix('maestrom')->group(function () {
    Route::get('/horario/{tarjeta}', [MaestroMController::class, 'getHorario']);
    Route::post('/tiene-reservacion', [MaestroMController::class, 'tieneReservacion']);
    Route::post('/reservacion-actual', [MaestroMController::class, 'getReservacionActual']);
    Route::post('/aula-disponible', [MaestroMController::class, 'aulaDisponible']);
    Route::post('/reservar', [MaestroMController::class, 'reservarAula']);
    Route::delete('/eliminar-reservacion', [MaestroMController::class, 'eliminarReservacion']);
    Route::get('/verificar-aula/{clave_aula}', [MaestroMController::class, 'verificarAula']);
    Route::post('/bitacora', [MaestroMController::class, 'registrarBitacora']);
    Route::get('/aulas', [MaestroMController::class, 'getAulas']);
    Route::get('/edificios', [MaestroMController::class, 'getEdificios']);
});

use App\Http\Controllers\Api\AlumnoController;

Route::prefix('alumno')->group(function () {
    Route::get('/horario/{numeroControl}', [AlumnoController::class, 'getHorario']);
    Route::post('/registrar', [AlumnoController::class, 'registrarAlumno']);
    Route::post('/cambiar-contrasena', [AlumnoController::class, 'cambiarContrasena']);
    Route::get('/computadoras', [AlumnoController::class, 'computadorasDisponibles']);
    Route::post('/reservacion/activa', [AlumnoController::class, 'tieneReservacionActiva']);
    Route::post('/reservacion/hoy', [AlumnoController::class, 'yaReservoHoy']);
    Route::post('/reservaciones', [AlumnoController::class, 'reservacionesActivas']);
    Route::post('/reservar', [AlumnoController::class, 'reservarComputadora']);
    Route::put('/extender-reserva', [AlumnoController::class, 'extenderReserva']);
    Route::delete('/cancelar-reserva', [AlumnoController::class, 'cancelarReserva']);
    Route::post('/bitacora', [AlumnoController::class, 'registrarBitacora']);


    Route::get('/', [AlumnoController::class, 'index']);
    Route::get('/{numeroControl}', [AlumnoController::class, 'show']);
    Route::post('/', [AlumnoController::class, 'store']);
    Route::put('/{numeroControl}', [AlumnoController::class, 'update']);
    Route::delete('/{numeroControl}', [AlumnoController::class, 'destroy']);

});

use App\Http\Controllers\Api\InventarioController;

Route::prefix('inventario')->group(function () {
    Route::get('/equipo/{num_inventario}/verificar', [InventarioController::class, 'verificarEquipo']);
    Route::put('/equipo/{inventario}/reservado', [InventarioController::class, 'marcarReservado']);
    Route::put('/equipo/{inventario}/ocupado', [InventarioController::class, 'marcarOcupado']);
    Route::put('/equipo/{inventario}/liberar', [InventarioController::class, 'liberarEquipo']);
});

use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::post('/cambiar-contrasena', [AuthController::class, 'changePassword']);