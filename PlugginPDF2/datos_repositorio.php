<?php
/**
 * Función principal para procesar datos según el tipo de plantilla
 * 
 * @param clsTinyButStrong $TBS Instancia de TinyButStrong
 * @param array $datos Datos a procesar
 * @param string $tipo_plantilla Tipo de plantilla
 */
function procesarDatosParaPlantilla($TBS, $datos, $tipo_plantilla) {
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
function procesarDatosLiberacionDocente($TBS, $datos) {
    // Datos básicos de la liberación
    $TBS->MergeField('id_liberacion', $datos['id_liberacion'] ?? '');
    $TBS->MergeField('fecha_liberacion', $datos['fecha_liberacion'] ?? '');
    $TBS->MergeField('otorga_liberacion', $datos['otorga_liberacion'] ? 'SÍ' : 'NO');
    
    // Datos del maestro
    $TBS->MergeField('tarjeta_maestro', $datos['tarjeta'] ?? '');
    $TBS->MergeField('nombre_maestro', $datos['nombre'] ?? '');
    $TBS->MergeField('apellido_paterno', $datos['apellidopaterno'] ?? '');
    $TBS->MergeField('apellido_materno', $datos['apellidomaterno'] ?? '');
    $TBS->MergeField('nombre_completo', 
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
function procesarDatosAvanceProgramatico($TBS, $datos) {
    $TBS->MergeField('id_avance', $datos['id_avance'] ?? '');
    $TBS->MergeField('clave_asignatura', $datos['clave_asignatura'] ?? '');
    $TBS->MergeField('nombre_asignatura', $datos['nombre_asignatura'] ?? '');
    $TBS->MergeField('tarjeta_profesor', $datos['tarjeta_profesor'] ?? '');
    $TBS->MergeField('nombre_profesor', $datos['nombre_profesor'] ?? '');
    $TBS->MergeField('nombre_completo_profesor', 
        ($datos['nombre_profesor'] ?? '') . ' ' . 
        ($datos['apellidopaterno'] ?? '') . ' ' . 
        ($datos['apellidomaterno'] ?? '')
    );
    $TBS->MergeField('periodo_nombre', $datos['nombre_periodo'] ?? '');
    $TBS->MergeField('periodo_codigo', $datos['codigoperiodo'] ?? '');
    $TBS->MergeField('fecha_creacion', $datos['fecha_creacion'] ?? '');
    $TBS->MergeField('estado', $datos['estado'] ?? '');
}

/**
 * Procesar datos para instrumentación
 */
function procesarDatosInstrumentacion($TBS, $datos) {
    $TBS->MergeField('id_instrumentacion', $datos['id_instrumentacion'] ?? '');
    $TBS->MergeField('nombre_instrumento', $datos['nombre_instrumento'] ?? '');
    $TBS->MergeField('valor_total', $datos['valor_total'] ?? '');
    $TBS->MergeField('tarjeta_profesor', $datos['tarjeta_profesor'] ?? '');
    $TBS->MergeField('nombre_profesor', $datos['nombre_profesor'] ?? '');
    $TBS->MergeField('periodo_nombre', $datos['nombre_periodo'] ?? '');
    $TBS->MergeField('periodo_codigo', $datos['codigoperiodo'] ?? '');
}

// Funciones de procesamiento para otros tipos de plantillas...
function procesarDatosReporteFinal($TBS, $datos) {
    // Implementar según necesidades
}

function procesarDatosLiberacionAcademica($TBS, $datos) {
    // Implementar según necesidades
}