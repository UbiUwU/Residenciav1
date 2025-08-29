<?php
/**
 * Función principal para procesar datos según el tipo de plantilla
 * 
 * @param clsTinyButStrong $TBS Instancia de TinyButStrong
 * @param array $datos Datos a procesar
 * @param string $tipo_plantilla Tipo de plantilla
 */
function procesarDatosParaPlantilla($TBS, $datos, $tipo_plantilla)
{
    // Agregar información común a todos los documentos
    $TBS->MergeField('fecha_generacion', date('d/m/Y'));
    $TBS->MergeField('hora_generacion', date('H:i:s'));

    // Delegar al procesador específico según el tipo de plantilla
    switch ($tipo_plantilla) {
        case 'libedocente':
            procesarDatosLiberacionDocente($TBS, $datos);
            break;

        case 'avance':
            procesarDatosAvanceProgramatico($TBS, $datos);
            break;

        case 'instrumentacion':
            procesarDatosInstrumentacion($TBS, $datos);
            break;

        case 'reportefinal':
            procesarDatosReporteFinal($TBS, $datos);
            break;

        case 'libeacademica':
            procesarDatosLiberacionAcademica($TBS, $datos);
            break;
        case 'comision':
            procesarDatosComisiones($TBS, $datos);

        default:
            // Procesamiento genérico como fallback
            foreach ($datos as $campo => $valor) {
                $TBS->MergeField($campo, $valor);
            }
            break;
    }
}

/**
 * Procesar datos para liberación docente
 */
function procesarDatosLiberacionDocente($TBS, $datos)
{
    // Datos básicos de la liberación
    $TBS->MergeField('id_liberacion', $datos['id_liberacion'] ?? '');
    $TBS->MergeField('fecha_liberacion', $datos['fecha_liberacion'] ?? '');
    $TBS->MergeField('otorga_liberacion', $datos['otorga_liberacion'] ? 'SÍ' : 'NO');

    // Datos del maestro
    $TBS->MergeField('tarjeta_maestro', $datos['tarjeta'] ?? '');
    $TBS->MergeField('nombre_maestro', $datos['nombre'] ?? '');
    $TBS->MergeField('apellido_paterno', $datos['apellidopaterno'] ?? '');
    $TBS->MergeField('apellido_materno', $datos['apellidomaterno'] ?? '');
    $TBS->MergeField(
        'nombre_completo',
        ($datos['nombre'] ?? '') . ' ' .
        ($datos['apellidopaterno'] ?? '') . ' ' .
        ($datos['apellidomaterno'] ?? '')
    );

    // Datos del departamento
    $TBS->MergeField('departamento', $datos['departamento'] ?? '');
    $TBS->MergeField('departamento_abrev', $datos['departamento_abrev'] ?? '');

    // Datos del periodo escolar
    $TBS->MergeField('periodo_nombre', $datos['nombre_periodo'] ?? '');
    $TBS->MergeField('periodo_codigo', $datos['codigoperiodo'] ?? '');
    $TBS->MergeField('periodo_inicio', $datos['fecha_inicio'] ?? '');
    $TBS->MergeField('periodo_fin', $datos['fecha_fin'] ?? '');

    // Estadísticas de actividades
    $TBS->MergeField('total_actividades', $datos['total_actividades'] ?? 0);
    $TBS->MergeField('actividades_si', $datos['actividades_si'] ?? 0);
    $TBS->MergeField('actividades_no', $datos['actividades_no'] ?? 0);
    $TBS->MergeField('actividades_na', $datos['actividades_na'] ?? 0);

    // Procesar actividades para la plantilla
    if (!empty($datos['actividades'])) {
        $TBS->MergeBlock('actividad', $datos['actividades']);
    }

    // Fecha actual
    $TBS->MergeField('fecha_actual', date('d/m/Y'));
}

/**
 * Procesar datos para avance programático
 */
function procesarDatosAvanceProgramatico($TBS, $datos)
{
    // Datos básicos del avance
    $TBS->MergeField('id_avance', $datos['id_avance'] ?? '');
    $TBS->MergeField('clave_asignatura', $datos['clave_asignatura'] ?? '');
    $TBS->MergeField('nombre_asignatura', $datos['nombre_asignatura'] ?? '');
    $TBS->MergeField('creditos', $datos['Creditos'] ?? 0);
    $TBS->MergeField('satca_teoricas', $datos['Satca_Teoricas'] ?? 0);
    $TBS->MergeField('satca_practicas', $datos['Satca_Practicas'] ?? 0);
    $TBS->MergeField('satca_total', $datos['Satca_Total'] ?? 0);
    $TBS->MergeField('estado', $datos['estado'] ?? '');
    // Datos de la carrera
    $TBS->MergeField('clave_carrera', $datos['clavecarrera'] ?? '');
    $TBS->MergeField('nombre_carrera', $datos['nombre_carrera'] ?? '');


    // Datos del maestro
    $TBS->MergeField('tarjeta_profesor', $datos['tarjeta_profesor'] ?? '');
    $TBS->MergeField('nombre_maestro', $datos['nombre_maestro'] ?? '');
    $TBS->MergeField('apellido_paterno', $datos['apellidopaterno'] ?? '');
    $TBS->MergeField('apellido_materno', $datos['apellidomaterno'] ?? '');
    $TBS->MergeField(
        'nombre_completo_maestro',
        ($datos['nombre_maestro'] ?? '') . ' ' .
        ($datos['apellidopaterno'] ?? '') . ' ' .
        ($datos['apellidomaterno'] ?? '')
    );

    
    // Datos del departamento
    $TBS->MergeField('departamento', $datos['departamento'] ?? '');
    $TBS->MergeField('departamento_abrev', $datos['departamento_abrev'] ?? '');

    // Datos del periodo escolar
    $TBS->MergeField('periodo_nombre', $datos['nombre_periodo'] ?? '');
    $TBS->MergeField('periodo_codigo', $datos['codigoperiodo'] ?? '');
    $TBS->MergeField('periodo_inicio', $datos['fecha_inicio'] ?? '');
    $TBS->MergeField('periodo_fin', $datos['fecha_fin'] ?? '');

    // Información del horario
    $TBS->MergeField('clave_grupo', $datos['clavegrupo'] ?? '');
    $TBS->MergeField('clave_aula', $datos['claveaula'] ?? '');

    error_log("=== ESTRUCTURA DE DATOS TEMAS ===");
    error_log(print_r($datos['temas'], true));
    error_log("=================================");

    // Procesar temas y subtemas
    if (!empty($datos['temas'])) {
        foreach ($datos['temas'] as &$tema) {
            // Formatear porcentaje
            $tema['porcentaje_formateado'] = number_format($tema['porcentaje_aprobacion'], 2) . '%';
            
            // Estado del tema
            $tema['estado_tema'] = $tema['porcentaje_aprobacion'] >= 100 ? 'COMPLETADO' : 'EN PROGRESO';
            
            // Formatear fechas
            if (!empty($tema['fecha_inicio'])) {
                $tema['fecha_inicio_formateada'] = date('d/m/Y', strtotime($tema['fecha_inicio']));
            }
            if (!empty($tema['fecha_fin'])) {
                $tema['fecha_fin_formateada'] = date('d/m/Y', strtotime($tema['fecha_fin']));
            }
            if (!empty($tema['fecha_inicio_real'])) {
                $tema['fecha_inicio_real_formateada'] = date('d/m/Y', strtotime($tema['fecha_inicio_real']));
            }
            if (!empty($tema['fecha_fin_real'])) {
                $tema['fecha_fin_real_formateada'] = date('d/m/Y', strtotime($tema['fecha_fin_real']));
            }
            if (!empty($tema['fecha_evaluacion'])) {
                $tema['fecha_evaluacion_formateada'] = date('d/m/Y', strtotime($tema['fecha_evaluacion']));
            }
            if (!empty($tema['fecha_evaluacion_real'])) {
                $tema['fecha_evaluacion_real_formateada'] = date('d/m/Y', strtotime($tema['fecha_evaluacion_real']));
            }
            
            // Crear una cadena con todos los subtemas para mostrar como texto
            $tema['lista_subtemas'] = '';
            if (!empty($tema['subtemas'])) {
                $subtemas_texto = [];
                foreach ($tema['subtemas'] as $subtema) {
                    $subtemas_texto[] = $subtema['orden_subtema'] . '. ' . $subtema['nombre_subtema'];
                }
                $tema['lista_subtemas'] = implode("\n", $subtemas_texto);
            }
        }
        
        
        // Bloque principal de temas
        $TBS->MergeBlock('tema', $datos['temas']);
    }

    // Procesar fechas clave
    if (!empty($datos['fechas_clave'])) {
        foreach ($datos['fechas_clave'] as &$fecha) {
            if (!empty($fecha['fecha_prevista'])) {
                $fecha['fecha_prevista_formateada'] = date('d/m/Y', strtotime($fecha['fecha_prevista']));
            }
            if (!empty($fecha['fecha_real'])) {
                $fecha['fecha_real_formateada'] = date('d/m/Y', strtotime($fecha['fecha_real']));
            }
        }
        $TBS->MergeBlock('fecha_clave', $datos['fechas_clave']);
    }

    // Procesar estadísticas
    if (!empty($datos['estadisticas'])) {
        $TBS->MergeField('total_temas', $datos['estadisticas']['total_temas'] ?? 0);
        $TBS->MergeField('temas_completados', $datos['estadisticas']['temas_completados'] ?? 0);
        $TBS->MergeField('porcentaje_total', $datos['estadisticas']['porcentaje_total'] ?? 0);
        $TBS->MergeField('promedio_aprobacion', $datos['estadisticas']['promedio_aprobacion'] ?? 0);
        $TBS->MergeField('temas_pendientes_firma', $datos['estadisticas']['temas_pendientes_firma'] ?? 0);
    }

    // Información de firmas
    $TBS->MergeField('requiere_firma_jefe', $datos['requiere_firma_jefe'] ? 'SÍ' : 'NO');
    $TBS->MergeField('firma_profesor', $datos['firma_profesor'] ?? 'PENDIENTE');
    $TBS->MergeField('firma_jefe_carrera', $datos['firma_jefe_carrera'] ?? 'PENDIENTE');

    // Fechas del avance
    $TBS->MergeField('fecha_creacion', $datos['fecha_creacion'] ?? '');
    $TBS->MergeField('fecha_ultima_actualizacion', $datos['fecha_ultima_actualizacion'] ?? '');

    // Fecha actual
    $TBS->MergeField('fecha_actual', date('d/m/Y'));
    $TBS->MergeField('hora_actual', date('H:i:s'));
}

/**
 * Procesar datos para instrumentación
 */
function procesarDatosInstrumentacion($TBS, $datos)
{
    $TBS->MergeField('id_instrumentacion', $datos['id_instrumentacion'] ?? '');
    $TBS->MergeField('nombre_instrumento', $datos['nombre_instrumento'] ?? '');
    $TBS->MergeField('valor_total', $datos['valor_total'] ?? '');
    $TBS->MergeField('tarjeta_profesor', $datos['tarjeta_profesor'] ?? '');
    $TBS->MergeField('nombre_profesor', $datos['nombre_profesor'] ?? '');
    $TBS->MergeField('periodo_nombre', $datos['nombre_periodo'] ?? '');
    $TBS->MergeField('periodo_codigo', $datos['codigoperiodo'] ?? '');
}

// Funciones de procesamiento para otros tipos de plantillas...
function procesarDatosReporteFinal($TBS, $datos)
{
    // Datos básicos del reporte
    $TBS->MergeField('id_reportefinal', $datos['id_reportefinal'] ?? '');
    $TBS->MergeField('estado', $datos['estado'] ?? '');

    // Datos del maestro
    $TBS->MergeField('tarjeta_profesor', $datos['tarjeta_profesor'] ?? '');
    $TBS->MergeField('nombre_maestro', $datos['nombre_maestro'] ?? '');
    $TBS->MergeField('apellido_paterno', $datos['apellidopaterno'] ?? '');
    $TBS->MergeField('apellido_materno', $datos['apellidomaterno'] ?? '');
    $TBS->MergeField(
        'nombre_completo_maestro',
        ($datos['nombre_maestro'] ?? '') . ' ' .
        ($datos['apellidopaterno'] ?? '') . ' ' .
        ($datos['apellidomaterno'] ?? '')
    );

    // Datos del departamento
    $TBS->MergeField('departamento', $datos['departamento'] ?? '');
    $TBS->MergeField('departamento_abrev', $datos['departamento_abrev'] ?? '');

    // Datos del periodo escolar
    $TBS->MergeField('periodo_nombre', $datos['nombre_periodo'] ?? '');
    $TBS->MergeField('periodo_codigo', $datos['codigoperiodo'] ?? '');
    $TBS->MergeField('periodo_inicio', $datos['fecha_inicio'] ?? '');
    $TBS->MergeField('periodo_fin', $datos['fecha_fin'] ?? '');

    // Datos estáticos
    $TBS->MergeField('numero_grupos_atendidos', $datos['numero_grupos_atendidos'] ?? 0);
    $TBS->MergeField('numero_estudiantes', $datos['numero_estudiantes'] ?? 0);
    $TBS->MergeField('numero_asignaturas_diferentes', $datos['numero_asignaturas_diferentes'] ?? 0);

    // Procesar asignaturas para la plantilla (en formato tabla)
    if (!empty($datos['asignaturas'])) {
        $TBS->MergeBlock('asignatura', $datos['asignaturas']);
    }

    // Procesar totales
    if (!empty($datos['totales'])) {
        $TBS->MergeField('total_a', $datos['totales']['total_a'] ?? 0);
        $TBS->MergeField('total_b', $datos['totales']['total_b'] ?? 0);
        $TBS->MergeField('total_bco', $datos['totales']['total_bco'] ?? 0);
        $TBS->MergeField('total_c', $datos['totales']['total_c'] ?? 0);
        $TBS->MergeField('total_d', $datos['totales']['total_d'] ?? 0);
        $TBS->MergeField('total_e', $datos['totales']['total_e'] ?? 0);
        $TBS->MergeField('total_f', $datos['totales']['total_f'] ?? 0);
        $TBS->MergeField('total_g', $datos['totales']['total_g'] ?? 0);
        $TBS->MergeField('total_h', $datos['totales']['total_h'] ?? 0);
        $TBS->MergeField('total_asignaturas', $datos['totales']['total_asignaturas'] ?? 0);
    }

    // Fecha actual
    $TBS->MergeField('fecha_actual', date('d/m/Y'));
    $TBS->MergeField('hora_actual', date('H:i:s'));
}

function procesarDatosLiberacionAcademica($TBS, $datos)
{
    // Datos básicos de la liberación académica
    $TBS->MergeField('id_liberacion_academica', $datos['id_liberacion'] ?? '');
    $TBS->MergeField('fecha_liberacion_academica', $datos['fecha_liberacion'] ?? '');
    $TBS->MergeField('otorga_liberacion_academica', $datos['otorga_liberacion'] ? 'SÍ' : 'NO');

    // Datos del maestro
    $TBS->MergeField('tarjeta_maestro', $datos['tarjeta'] ?? '');
    $TBS->MergeField('nombre_maestro', $datos['nombre'] ?? '');
    $TBS->MergeField('apellido_paterno', $datos['apellidopaterno'] ?? '');
    $TBS->MergeField('apellido_materno', $datos['apellidomaterno'] ?? '');
    $TBS->MergeField(
        'nombre_completo_maestro',
        ($datos['nombre'] ?? '') . ' ' .
        ($datos['apellidopaterno'] ?? '') . ' ' .
        ($datos['apellidomaterno'] ?? '')
    );

    // Datos del departamento
    $TBS->MergeField('departamento_academico', $datos['departamento'] ?? '');
    $TBS->MergeField('departamento_abrev_academico', $datos['departamento_abrev'] ?? '');

    // Datos del periodo escolar
    $TBS->MergeField('periodo_nombre_academico', $datos['nombre_periodo'] ?? '');
    $TBS->MergeField('periodo_codigo_academico', $datos['codigoperiodo'] ?? '');
    $TBS->MergeField('periodo_inicio_academico', $datos['fecha_inicio'] ?? '');
    $TBS->MergeField('periodo_fin_academico', $datos['fecha_fin'] ?? '');

    // Estadísticas de actividades académicas
    $TBS->MergeField('total_actividades_academicas', $datos['total_actividades'] ?? 0);
    $TBS->MergeField('actividades_si_academicas', $datos['actividades_si'] ?? 0);
    $TBS->MergeField('actividades_no_academicas', $datos['actividades_no'] ?? 0);
    $TBS->MergeField('actividades_na_academicas', $datos['actividades_na'] ?? 0);

    // Información adicional académica
    if (!empty($datos['informacion_adicional'])) {
        $info = $datos['informacion_adicional'];
        $TBS->MergeField('nivel_educativo', $info['nivel_educativo'] ?? '');
        $TBS->MergeField('programa_educativo', $info['programa_educativo'] ?? '');
        $TBS->MergeField('modalidad', $info['modalidad'] ?? '');
        $TBS->MergeField('creditos_impartidos', $info['creditos_impartidos'] ?? 0);
        $TBS->MergeField('estudiantes_atendidos', $info['estudiantes_atendidos'] ?? 0);
        $TBS->MergeField('promedio_calificaciones', $info['promedio_calificaciones'] ?? 0);
    }

    if (!empty($datos['actividades'])) {
        $TBS->MergeBlock('actividad', $datos['actividades']);
    }

    // Fecha actual
    $TBS->MergeField('fecha_actual', date('d/m/Y'));
    $TBS->MergeField('hora_actual', date('H:i:s'));

}


function procesarDatosComisiones($TBS, $datos)
{
    // Datos básicos de la comisión
    $TBS->MergeField('id_comision', $datos['id_comision'] ?? '');
    $TBS->MergeField('folio', $datos['folio'] ?? '');
    $TBS->MergeField('nombre_evento', $datos['nombre_evento'] ?? '');
    $TBS->MergeField('estado', $datos['estado'] ?? '');
    $TBS->MergeField('remitente_nombre', $datos['remitente_nombre'] ?? '');
    $TBS->MergeField('remitente_puesto', $datos['remitente_puesto'] ?? '');
    $TBS->MergeField('lugar', $datos['lugar'] ?? '');
    $TBS->MergeField('motivo', $datos['motivo'] ?? '');
    $TBS->MergeField('tipo_comision', $datos['tipo_comision'] ?? '');
    $TBS->MergeField('permiso_constancia', $datos['permiso_constancia'] ? 'SÍ' : 'NO');
    $TBS->MergeField('tipo_evento', $datos['tipo_evento'] ?? '');

    // Datos del periodo escolar
    $TBS->MergeField('periodo_nombre', $datos['nombre_periodo'] ?? '');
    $TBS->MergeField('periodo_codigo', $datos['codigoperiodo'] ?? '');
    $TBS->MergeField('periodo_inicio', $datos['fecha_inicio'] ?? '');
    $TBS->MergeField('periodo_fin', $datos['fecha_fin'] ?? '');

    // Datos del maestro
    $TBS->MergeField('tarjeta_maestro', $datos['tarjeta'] ?? '');
    $TBS->MergeField('nombre_maestro', $datos['nombre_maestro'] ?? '');
    $TBS->MergeField('apellido_paterno', $datos['apellidopaterno'] ?? '');
    $TBS->MergeField('apellido_materno', $datos['apellidomaterno'] ?? '');
    $TBS->MergeField(
        'nombre_completo_maestro',
        ($datos['nombre_maestro'] ?? '') . ' ' .
        ($datos['apellidopaterno'] ?? '') . ' ' .
        ($datos['apellidomaterno'] ?? '')
    );

    // Datos del departamento
    $TBS->MergeField('departamento', $datos['departamento'] ?? '');
    $TBS->MergeField('departamento_abrev', $datos['departamento_abrev'] ?? '');

    // Duración total
    $TBS->MergeField('duracion_total', $datos['duracion_total'] ?? 0);
    $TBS->MergeField('duracion_total_horas', round($datos['duracion_total'] ?? 0, 2));

    // Procesar fechas de la comisión
    if (!empty($datos['fechas'])) {
        foreach ($datos['fechas'] as &$fecha) {
            // Formatear fechas y horas
            if (!empty($fecha['fecha'])) {
                $fecha['fecha_formateada'] = date('d/m/Y', strtotime($fecha['fecha']));
            }
            if (!empty($fecha['hora_inicio'])) {
                $fecha['hora_inicio_formateada'] = date('H:i', strtotime($fecha['hora_inicio']));
            }
            if (!empty($fecha['hora_fin'])) {
                $fecha['hora_fin_formateada'] = date('H:i', strtotime($fecha['hora_fin']));
            }

            // Calcular duración de cada fecha
            if (!empty($fecha['duracion'])) {
                $fecha['duracion_horas'] = convertirIntervaloAHoras($fecha['duracion']);
            } elseif (!empty($fecha['hora_inicio']) && !empty($fecha['hora_fin'])) {
                $inicio = new DateTime($fecha['hora_inicio']);
                $fin = new DateTime($fecha['hora_fin']);
                $diferencia = $inicio->diff($fin);
                $fecha['duracion_horas'] = $diferencia->h + ($diferencia->i / 60);
            } else {
                $fecha['duracion_horas'] = 0;
            }
        }
        $TBS->MergeBlock('fecha_comision', $datos['fechas']);
    }

    // Calcular información resumen
    $total_dias = count($datos['fechas'] ?? []);
    $TBS->MergeField('total_dias', $total_dias);

    // Fecha actual
    $TBS->MergeField('fecha_actual', date('d/m/Y'));
    $TBS->MergeField('hora_actual', date('H:i:s'));
}