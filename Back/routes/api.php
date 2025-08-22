<?php

use App\Http\Controllers\CatalogoTiposFechaController;
use App\Http\Controllers\ComisionController;
use App\Http\Controllers\CompetenciaController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\EdificioController;
use App\Http\Controllers\FechasClavePeriodoController;
use App\Http\Controllers\PracticaController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TipoEventoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\MaestroController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PeriodoEscolarController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\ComputadoraController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\PresentacionController;
use App\Http\Controllers\DisenoController;
use App\Http\Controllers\AvanceController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HorarioAsignaturaMaestroController;
use App\Http\Controllers\AvanceDetalleController;
use App\Http\Controllers\AvanceDetalleFechaController;
use App\Http\Controllers\AvanceFechaController;
use App\Http\Controllers\AlumnoReworkController;


// routes/api.php
Route::get('/enum/{tipo}', [App\Http\Controllers\EnumController::class, 'getValores']);


Route::prefix('avance')->group(function () {
    Route::get('/', [AvanceController::class, 'index']); // Listar todos
    Route::post('/', [AvanceController::class, 'store']); // Crear nuevo
    Route::get('/{id}', [AvanceController::class, 'show']); // Mostrar uno
    Route::put('/{id}', [AvanceController::class, 'update']); // Actualizar
    Route::delete('/{id}', [AvanceController::class, 'destroy']); // Eliminar
});

Route::prefix('avancedetalles')->group(function () {
    Route::get('/', [AvanceDetalleController::class, 'index']); // Listar todos
    Route::post('/', [AvanceDetalleController::class, 'store']); // Crear nuevo
    Route::get('/{id}', [AvanceDetalleController::class, 'show']); // Mostrar uno
    Route::put('/{id}', [AvanceDetalleController::class, 'update']); // Actualizar
    Route::delete('/{id}', [AvanceDetalleController::class, 'destroy']); // Eliminar
});

Route::prefix('avancedetallefechas')->group(function () {
    Route::get('/', [AvanceDetalleFechaController::class, 'index']); // Listar todos
    Route::post('/', [AvanceDetalleFechaController::class, 'store']); // Crear nuevo
    Route::get('/{id}', [AvanceDetalleFechaController::class, 'show']); // Mostrar uno
    Route::put('/{id}', [AvanceDetalleFechaController::class, 'update']); // Actualizar
    Route::delete('/{id}', [AvanceDetalleFechaController::class, 'destroy']); // Eliminar
});

Route::prefix('avancefechas')->group(function () {
    Route::get('/', [AvanceFechaController::class, 'index']); // Listar todos
    Route::post('/', [AvanceFechaController::class, 'store']); // Crear nuevo
    Route::get('/{id}', [AvanceFechaController::class, 'show']); // Mostrar uno
    Route::put('/{id}', [AvanceFechaController::class, 'update']); // Actualizar
    Route::delete('/{id}', [AvanceFechaController::class, 'destroy']); // Eliminar
});

Route::prefix('maestros')->group(function () {
    Route::get('/', [MaestroController::class, 'index']); // Listar todos
    Route::get('/clean', [MaestroController::class, 'indexL']); // Listar maestros con info básica
    Route::post('/', [MaestroController::class, 'store']); // Crear nuevo
    Route::get('/{id}', [MaestroController::class, 'show']); // Mostrar uno
    Route::put('/{id}', [MaestroController::class, 'update']); // Actualizar
    Route::delete('/{id}', [MaestroController::class, 'destroy']); // Eliminar
    Route::get('/ListaM/{tarjeta}', [MaestroController::class, 'ListaM']);
    Route::get('/departamento/{idDepartamento}', [MaestroController::class, 'indexByDepartamentoBasic']);
});

use App\Http\Controllers\EstadisticasMaestroController;

Route::get('/estadisticasmaestro/tarjeta/{tarjeta}/periodo/{id_periodo_escolar}', [EstadisticasMaestroController::class, 'estadisticasPorPeriodo']);

use App\Http\Controllers\ReporteFinalController;

// Rutas para ReporteFinal
Route::get('reportesfinale', [ReporteFinalController::class, 'index']);
Route::post('reportesfinale', [ReporteFinalController::class, 'store']);
Route::get('reportesfinale/{id}', [ReporteFinalController::class, 'show']);
Route::put('reportesfinale/{id}', [ReporteFinalController::class, 'update']);
Route::delete('reportesfinale/{id}', [ReporteFinalController::class, 'destroy']);

// Rutas adicionales
Route::get('reportesfinales/maestro/{tarjeta}/periodo/{id_periodo_escolar}', [ReporteFinalController::class, 'getByMaestroPeriodo']);
Route::put('reportesfinales/{id}/cambiar-estado', [ReporteFinalController::class, 'cambiarEstado']);

use App\Http\Controllers\DatosEstaticosReporteFinalController;
use App\Http\Controllers\ReporteFinalAsignaturaController;

Route::put('DatosReportefinal/{id_reportefinal}', [DatosEstaticosReporteFinalController::class, 'update']);
Route::delete('DatosReportefinal/{id_reportefinal}/datos-estaticos', [DatosEstaticosReporteFinalController::class, 'destroy']);

Route::put('asignaturasReporteFinales/{id}', [ReporteFinalAsignaturaController::class, 'update']);
Route::delete('reportes-finales/asignaturas/{id}', [ReporteFinalAsignaturaController::class, 'destroy']);
Route::put('asignaturasReporteFinales/M/{id_reportefinal}', [ReporteFinalAsignaturaController::class, 'updateMultiple']);


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

Route::prefix('periodos')->group(function () {
    Route::post('/', [PeriodoEscolarController::class, 'store']);
    Route::get('/', [PeriodoEscolarController::class, 'index']);
    Route::get('/clean', [PeriodoEscolarController::class, 'indexL']);
    Route::get('/cleanfc', [PeriodoEscolarController::class, 'indexLFC']);
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
    Route::get('/', [GrupoController::class, 'index']);          // Listar todos
    Route::get('/{clavegrupo}', [GrupoController::class, 'show']); // Mostrar uno
    Route::post('/', [GrupoController::class, 'store']);         // Crear
    Route::put('/{clavegrupo}', [GrupoController::class, 'update']); // Actualizar
    Route::delete('/{clavegrupo}', [GrupoController::class, 'destroy']); // Eliminar
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
    Route::get('/clean', [AsignaturaController::class, 'indexC']);           // Listar todas
    Route::get('/carrera/{clavecarrera}', [AsignaturaController::class, 'indexByCarrera']); // Filtrar por carrera
    Route::get('/{ClaveAsignatura}', [AsignaturaController::class, 'show']); // Mostrar 1
    Route::post('/', [AsignaturaController::class, 'store']);              // Crear nueva
    Route::put('/{ClaveAsignatura}', [AsignaturaController::class, 'update']); // Actualizar
    Route::delete('/{ClaveAsignatura}', [AsignaturaController::class, 'destroy']); // Eliminar


    Route::get('/maestro/{clave}', [AsignaturaController::class, 'getByTarjetaComplete']);
    Route::get('/grupos/{clave}', [AsignaturaController::class, 'getDetalleGruposByTarjeta']);
    Route::get('/complete/{clave}', [AsignaturaController::class, 'getByClaveComplete']);
    //Reporte
    Route::get('/asignaturas/generate-pdf', [AsignaturaController::class, 'generatePDF']);

});

Route::prefix('presentacion')->group(function () {
    Route::get('/', [PresentacionController::class, 'index']);
    Route::get('/{claveAsignatura}', [PresentacionController::class, 'show']);
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


Route::prefix('alumnosR')->group(function () {
    Route::get('/', [AlumnoReworkController::class, 'index']);           // Listar todas
    Route::get('/{id}', [AlumnoReworkController::class, 'show']);       // Mostrar 1
    Route::post('/', [AlumnoReworkController::class, 'store']);         // Crear nueva
    Route::put('/{id}', [AlumnoReworkController::class, 'update']);     // Actualizar
    Route::delete('/{id}', [AlumnoReworkController::class, 'destroy']); // Eliminar
});

use App\Http\Controllers\CargaAcademicaDetalleController;

Route::prefix('cargadetalles')->group(function () {
    Route::get('/', [CargaAcademicaDetalleController::class, 'index']);           // Listar todas
    Route::get('/{id}', [CargaAcademicaDetalleController::class, 'show']);       // Mostrar 1
    Route::post('/', [CargaAcademicaDetalleController::class, 'store']);         // Crear nueva
    Route::put('/{id}', [CargaAcademicaDetalleController::class, 'update']);     // Actualizar
    Route::delete('/{id}', [CargaAcademicaDetalleController::class, 'destroy']); // Eliminar
});

Route::prefix('comisiones')->group(function () {
    Route::get('/', [ComisionController::class, 'index']);           // Listar todas
    Route::get('/clean', [ComisionController::class, 'indexClean']); // Listar todas sin relaciones
    Route::get('/{id}', [ComisionController::class, 'show']);       // Mostrar 1
    Route::post('/', [ComisionController::class, 'store']);         // Crear nueva
    Route::put('/{id}', [ComisionController::class, 'update']);     // Actualizar
    Route::delete('/{id}', [ComisionController::class, 'destroy']); // Eliminar
    // Comisiones por período
    Route::get('periodo/{idPeriodoEscolar}', [ComisionController::class, 'indexByPeriodo']);
    // Comisiones por maestro
    Route::get('maestro/{tarjetaMaestro}', [ComisionController::class, 'indexByMaestro']);
    // Comisiones por período y maestro
    Route::get('/periodo/{idPeriodoEscolar}/{tarjetaMaestro}', [ComisionController::class, 'indexByPeriodoAndMaestro']);
});

Route::prefix('roles')->group(function () {
    Route::get('/', [RolController::class, 'index']);           // Listar todos los roles
    Route::get('/{id}', [RolController::class, 'show']);       // Mostrar un rol específico
    Route::post('/', [RolController::class, 'store']);         // Crear un nuevo rol
    Route::put('/{id}', [RolController::class, 'update']);     // Actualizar un rol existente
});

Route::prefix('usuarios')->group(function () {
    Route::get('/', [UsuarioController::class, 'index']);           // Listar todos los usuarios
    Route::get('/{id}', [UsuarioController::class, 'show']);       // Mostrar un usuario específico
    Route::post('/', [UsuarioController::class, 'store']);         // Crear un nuevo usuario
    Route::put('/{id}', [UsuarioController::class, 'update']);     // Actualizar un usuario existente
});

Route::prefix('departamentos')->group(function () {
    Route::get('/', [DepartamentoController::class, 'index']);        // Listar todos
    Route::get('/{id}', [DepartamentoController::class, 'show']);    // Mostrar uno
    Route::post('/', [DepartamentoController::class, 'store']);      // Crear
    Route::put('/{id}', [DepartamentoController::class, 'update']);  // Actualizar
    Route::delete('/{id}', [DepartamentoController::class, 'destroy']); // Eliminar
});

Route::prefix('edificios')->group(function () {
    Route::get('/', [EdificioController::class, 'index']);
    Route::get('/{clave}', [EdificioController::class, 'show']);
    Route::post('/', [EdificioController::class, 'store']);
    Route::put('/{clave}', [EdificioController::class, 'update']);
    Route::delete('/{clave}', [EdificioController::class, 'destroy']);
});

Route::prefix('aulas')->group(function () {
    Route::get('/', [AulaController::class, 'index']);
    Route::get('/{clave}', [AulaController::class, 'show']);
    Route::post('/', [AulaController::class, 'store']);
    Route::put('/{clave}', [AulaController::class, 'update']);
    Route::delete('/{clave}', [AulaController::class, 'destroy']);
});

Route::prefix('tipoevento')->group(function () {
    Route::get('/', [TipoEventoController::class, 'index']);
    Route::get('/{clave}', [TipoEventoController::class, 'show']);
    Route::post('/', [TipoEventoController::class, 'store']);
    Route::put('/{clave}', [TipoEventoController::class, 'update']);
    Route::delete('/{clave}', [TipoEventoController::class, 'destroy']);
});

Route::prefix('carreras')->group(function () {
    Route::get('/', [CarreraController::class, 'index']);          // Listar todas
    Route::get('/{clavecarrera}', [CarreraController::class, 'show']); // Mostrar una
    Route::post('/', [CarreraController::class, 'store']);         // Crear
    Route::put('/{clavecarrera}', [CarreraController::class, 'update']); // Actualizar
    Route::delete('/{clavecarrera}', [CarreraController::class, 'destroy']); // Eliminar
});


Route::prefix('horario')->group(function () {
    Route::get('/', [HorarioAsignaturaMaestroController::class, 'index']);           // Listar todos los horarios
    Route::get('/{clavehorario}', [HorarioAsignaturaMaestroController::class, 'show']); // Mostrar un horario
    Route::post('/', [HorarioAsignaturaMaestroController::class, 'store']);          // Crear nuevo horario
    Route::put('/{clavehorario}', [HorarioAsignaturaMaestroController::class, 'update']); // Actualizar horario
    Route::delete('/{clavehorario}', [HorarioAsignaturaMaestroController::class, 'destroy']); // Eliminar horario
    Route::get('/{idperiodoescolar}/maestro/{tarjeta}', [HorarioAsignaturaMaestroController::class, 'indexByPeriodoAndMaestro']);
    Route::get('/periodo/{idperiodoescolar}/carrera/{clavecarrera}', [HorarioAsignaturaMaestroController::class, 'indexByPeriodoAndCarrera']);
});



Route::prefix('fechasclave')->group(function () {
    Route::get('/', [FechasClavePeriodoController::class, 'index']);           // Listar todos los horarios
    Route::get('/{clavehorario}', [FechasClavePeriodoController::class, 'show']); // Mostrar un horario
    Route::post('/', [FechasClavePeriodoController::class, 'store']);          // Crear nuevo horario
    Route::put('/{clavehorario}', [FechasClavePeriodoController::class, 'update']); // Actualizar horario
    Route::delete('/{clavehorario}', [FechasClavePeriodoController::class, 'destroy']); // Eliminar horario
});

Route::prefix('catalogofecha')->group(function () {
    Route::get('/', [CatalogoTiposFechaController::class, 'index']);           // Listar todos los horarios
    Route::get('/{clavehorario}', [CatalogoTiposFechaController::class, 'show']); // Mostrar un horario
    Route::post('/', [CatalogoTiposFechaController::class, 'store']);          // Crear nuevo horario
    Route::put('/{clavehorario}', [CatalogoTiposFechaController::class, 'update']); // Actualizar horario
    Route::delete('/{clavehorario}', [CatalogoTiposFechaController::class, 'destroy']); // Eliminar horario
});

//Esta zona es de la zona movil.
use App\Http\Controllers\Api\MaestroMController;

Route::prefix('maestro')->group(function () {
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
    Route::post('/bitacora/cerrar', [AlumnoController::class, 'cerrarBitacora']);
    // Ver bitácora completa de un alumno
    Route::get('/bitacora/{numerocontrol}', [AlumnoController::class, 'verBitacoraAlumno']);


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


use App\Http\Controllers\Api\NotificacionesController;

Route::prefix('notificaciones')->group(function () {
    Route::get('/', [NotificacionesController::class, 'index']);
    Route::get('/{Usuario_id}', [NotificacionesController::class, 'show']);
    Route::post('/', [NotificacionesController::class, 'store']);
    Route::put('/{id}', [NotificacionesController::class, 'update']);
    Route::delete('/{id}', [NotificacionesController::class, 'destroy']);
});