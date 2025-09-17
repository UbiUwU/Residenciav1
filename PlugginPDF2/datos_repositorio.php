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
    global $conn;
    // Delegar al procesador específico según el tipo de plantilla
    switch ($tipo_plantilla) {
        case 'libedocente':
            procesarDatosLiberacionDocente($TBS, $datos);
            break;

        case 'avance':
            procesarDatosAvanceProgramatico($TBS, $datos, $conn);
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
function procesarDatosAvanceProgramatico($TBS, $datos, $conn)
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
    $TBS->MergeField('clave_horario', $datos['clave_horario'] ?? '');

    // Horario por días (formatear horas si es necesario)
    $TBS->MergeField('lunes_hi', formatearHora($datos['lunes_hi'] ?? ''));
    $TBS->MergeField('lunes_hf', formatearHora($datos['lunes_hf'] ?? ''));
    $TBS->MergeField('martes_hi', formatearHora($datos['martes_hi'] ?? ''));
    $TBS->MergeField('martes_hf', formatearHora($datos['martes_hf'] ?? ''));
    $TBS->MergeField('miercoles_hi', formatearHora($datos['miercoles_hi'] ?? ''));
    $TBS->MergeField('miercoles_hf', formatearHora($datos['miercoles_hf'] ?? ''));
    $TBS->MergeField('jueves_hi', formatearHora($datos['jueves_hi'] ?? ''));
    $TBS->MergeField('jueves_hf', formatearHora($datos['jueves_hf'] ?? ''));
    $TBS->MergeField('viernes_hi', formatearHora($datos['viernes_hi'] ?? ''));
    $TBS->MergeField('viernes_hf', formatearHora($datos['viernes_hf'] ?? ''));
    $TBS->MergeField('sabado_hi', formatearHora($datos['sabado_hi'] ?? ''));
    $TBS->MergeField('sabado_hf', formatearHora($datos['sabado_hf'] ?? ''));

    // Información de días con clases (para mostrar en el horario resumido)
    $diasClase = [];
    if (!empty($datos['lunes_hi']))
        $diasClase[] = 'Lunes';
    if (!empty($datos['martes_hi']))
        $diasClase[] = 'Martes';
    if (!empty($datos['miercoles_hi']))
        $diasClase[] = 'Miércoles';
    if (!empty($datos['jueves_hi']))
        $diasClase[] = 'Jueves';
    if (!empty($datos['viernes_hi']))
        $diasClase[] = 'Viernes';
    if (!empty($datos['sabado_hi']))
        $diasClase[] = 'Sábado';

    $TBS->MergeField('dias_clase', implode(', ', $diasClase));
    $TBS->MergeField('total_dias_clase', count($diasClase));

    // Información de firmas
    $TBS->MergeField('requiere_firma_jefe', $datos['requiere_firma_jefe'] ? 'SÍ' : 'NO');
    $TBS->MergeField('firma_profesor', $datos['firma_profesor'] ? 'FIRMADO' : 'PENDIENTE');
    $TBS->MergeField('firma_jefe_carrera', $datos['firma_jefe_carrera'] ? 'FIRMADO' : 'PENDIENTE');

    // Fechas del avance
    $TBS->MergeField('fecha_creacion', formatearFecha($datos['fecha_creacion'] ?? ''));
    $TBS->MergeField('fecha_ultima_actualizacion', formatearFecha($datos['fecha_ultima_actualizacion'] ?? ''));

    // Fecha y hora actual
    $TBS->MergeField('fecha_actual', date('d/m/Y'));
    $TBS->MergeField('hora_actual', date('H:i:s'));
    // Procesar detalles (temas y subtemas) si existen
    if (!empty($datos['detalles'])) {
    $temasParaTemplate = [];

    foreach ($datos['detalles'] as $index => $tema) {
        $temaParaTemplate = [
            'id_avance_detalle' => $tema['id_avance_detalle'],
            'id_tema' => $tema['id_tema'],
            'porcentaje_aprobacion' => $tema['porcentaje_aprobacion'],
            'requiere_firma_docente' => $tema['requiere_firma_docente'],
            'observaciones' => $tema['observaciones'],
            'nombre_tema' => $tema['nombre_tema'],
            'numero_tema' => $tema['numero_tema'],
            'index' => $index + 1,
        ];

        // Procesar fechas
        if (!empty($tema['fechas'][0])) {
            $temaParaTemplate['fecha'] = $tema['fechas'][0];
        } else {
            $temaParaTemplate['fecha'] = [
                'fecha_inicio' => '',
                'fecha_fin' => '',
                'fecha_inicio_real' => '',
                'fecha_fin_real' => '',
                'fecha_evaluacion' => '',
                'fecha_evaluacion_real' => ''
            ];
        }

        // Obtener subtemas y convertirlos a string para el template
        $subtemasDelTema = obtenerSubtemas($conn, $tema['id_tema']);
        $subtemasTexto = '';
        
        foreach ($subtemasDelTema as $subtema) {
            if (!empty($subtema['Nombre_Subtema'])) {
                $subtemasTexto .= "" . $subtema['Orden'] . "." . $subtema['Nombre_Subtema'] . "\n";
            }
        }
        
        $temaParaTemplate['subtemas_texto'] = $subtemasTexto;
        $temasParaTemplate[] = $temaParaTemplate;
    }

    // Merge del bloque principal de temas
    $TBS->MergeBlock('bloque_temas', $temasParaTemplate);
}
}

// Función auxiliar para formatear horas
function formatearHora($hora)
{
    if (empty($hora))
        return '';

    // Si la hora viene en formato de tiempo de PostgreSQL
    if (strpos($hora, ':') !== false) {
        return date('H:i', strtotime($hora));
    }

    return $hora;
}

// Función auxiliar para formatear fechas
function formatearFecha($fecha)
{
    if (empty($fecha))
        return '';

    return date('d/m/Y', strtotime($fecha));
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