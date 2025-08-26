<?php
/**
 * Función principal para obtener datos según el tipo de plantilla
 * 
 * @param PDO $conn Conexión a la base de datos
 * @param string $tipo_plantilla Tipo de plantilla
 * @param int $periodo_id ID del periodo escolar
 * @param int $id_tabla ID específico del registro
 * @return array Datos para la plantilla
 */
function obtenerIdTipoPlantilla($conn, $nombre_tipo) {
    $query = "SELECT id FROM tipos_plantilla WHERE nombre = :nombre";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nombre', $nombre_tipo, PDO::PARAM_STR);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['id'] : false;
}

/**
 * Obtener plantilla activa por tipo y periodo
 */
function obtenerPlantillaActiva($conn, $tipo_id, $periodo_id) {
    $query = "
        SELECT p.*, tp.nombre as tipo_nombre, ep.nombre as estado_nombre
        FROM plantillas p
        INNER JOIN tipos_plantilla tp ON p.tipo_id = tp.id
        INNER JOIN estados_plantilla ep ON p.estado_id = ep.id
        WHERE p.tipo_id = :tipo_id 
        AND (p.periodo_escolar_id = :periodo_id OR p.periodo_escolar_id IS NULL)
        AND p.estado_id = (SELECT id FROM estados_plantilla WHERE nombre = 'activo')
        ORDER BY p.periodo_escolar_id DESC, p.creado_en DESC
        LIMIT 1
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':tipo_id', $tipo_id, PDO::PARAM_INT);
    $stmt->bindParam(':periodo_id', $periodo_id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Obtener plantilla por ID y tipo
 */
function obtenerPlantillaPorId($conn, $plantilla_id, $tipo_id) {
    $query = "
        SELECT p.*, tp.nombre as tipo_nombre, ep.nombre as estado_nombre
        FROM plantillas p
        INNER JOIN tipos_plantilla tp ON p.tipo_id = tp.id
        INNER JOIN estados_plantilla ep ON p.estado_id = ep.id
        WHERE p.id = :plantilla_id AND p.tipo_id = :tipo_id
        AND p.estado_id = (SELECT id FROM estados_plantilla WHERE nombre = 'activo')
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':plantilla_id', $plantilla_id, PDO::PARAM_INT);
    $stmt->bindParam(':tipo_id', $tipo_id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Función principal para obtener datos según el tipo de plantilla
 */
function obtenerDatosDesdeTabla($conn, $tipo_plantilla, $periodo_id, $id_tabla) {
    // Validar que el tipo de plantilla es válido
    $tipos_validos = ['avance', 'instrumentacion', 'reportefinal', 'libedocente', 'libeacademica'];
    
    if (!in_array($tipo_plantilla, $tipos_validos)) {
        throw new Exception("Tipo de plantilla no válido: $tipo_plantilla");
    }
    
    // Delegar a la función específica según el tipo de plantilla
    switch ($tipo_plantilla) {
        case 'libedocente':
            return obtenerDatosLiberacionDocente($conn, $id_tabla, $periodo_id);
            
        case 'avance':
            return obtenerDatosAvanceProgramatico($conn, $id_tabla, $periodo_id);
            
        case 'instrumentacion':
            return obtenerDatosInstrumentacion($conn, $id_tabla, $periodo_id);
            
        case 'reportefinal':
            return obtenerDatosReporteFinal($conn, $id_tabla, $periodo_id);
            
        case 'libeacademica':
            return obtenerDatosLiberacionAcademica($conn, $id_tabla, $periodo_id);
            
        default:
            throw new Exception("Tipo de plantilla no implementado: $tipo_plantilla");
    }
}

function obtenerInfoPeriodo($conn, $periodo_id) {
    $query = "
        SELECT id_periodo_escolar, codigoperiodo, nombre_periodo, fecha_inicio, fecha_fin
        FROM periodo_escolar 
        WHERE id_periodo_escolar = :periodo_id AND estado = true
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':periodo_id', $periodo_id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Función para obtener datos de liberación docente
 */
function obtenerDatosLiberacionDocente($conn, $id_liberacion, $periodo_id) {
    $query = "
        SELECT 
            ld.id_liberacion,
            ld.fecha_liberacion,
            ld.otorga_liberacion,
            m.tarjeta,
            m.nombre,
            m.apellidopaterno,
            m.apellidomaterno,
            d.nombre as departamento,
            d.abreviacion as departamento_abrev,
            pe.nombre_periodo,
            pe.fecha_inicio,
            pe.fecha_fin,
            pe.codigoperiodo,
            COUNT(ldd.id_detalle) as total_actividades,
            SUM(CASE WHEN ldd.si = true THEN 1 ELSE 0 END) as actividades_si,
            SUM(CASE WHEN ldd.no = true THEN 1 ELSE 0 END) as actividades_no,
            SUM(CASE WHEN ldd.na = true THEN 1 ELSE 0 END) as actividades_na
        FROM liberacion_docente ld
        INNER JOIN maestros m ON ld.tarjeta_maestro = m.tarjeta
        INNER JOIN departamentos d ON ld.id_departamento = d.id_departamento
        INNER JOIN periodo_escolar pe ON ld.id_periodo_escolar = pe.id_periodo_escolar
        LEFT JOIN liberacion_docente_detalles ldd ON ld.id_liberacion = ldd.id_liberacion
        WHERE ld.id_liberacion = :id_liberacion AND ld.id_periodo_escolar = :periodo_id
        GROUP BY ld.id_liberacion, m.tarjeta, d.nombre, d.abreviacion, pe.nombre_periodo, pe.fecha_inicio, pe.fecha_fin, pe.codigoperiodo
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_liberacion', $id_liberacion, PDO::PARAM_INT);
    $stmt->bindParam(':periodo_id', $periodo_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $liberacion = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Obtener detalles de las actividades
    if ($liberacion) {
        $liberacion['actividades'] = obtenerDetallesActividades($conn, $id_liberacion);
    }
    
    return $liberacion;
}

function obtenerDetallesActividades($conn, $id_liberacion) {
    $query = "
        SELECT 
            numero_actividad,
            descripcion_actividad,
            si,
            no,
            na
        FROM liberacion_docente_detalles
        WHERE id_liberacion = :id_liberacion
        ORDER BY numero_actividad
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_liberacion', $id_liberacion, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Función para obtener datos de avance programático
 */
function obtenerDatosAvanceProgramatico($conn, $id_avance, $periodo_id) {
    $query = "
        SELECT 
            a.*,
            m.nombre as nombre_profesor,
            m.apellidopaterno,
            m.apellidomaterno,
            asi.nombre_asignatura,
            pe.nombre_periodo,
            pe.codigoperiodo
        FROM avance a
        INNER JOIN maestros m ON a.tarjeta_profesor = m.tarjeta
        INNER JOIN asignatura asi ON a.clave_asignatura = asi.ClaveAsignatura
        INNER JOIN periodo_escolar pe ON a.id_periodo_escolar = pe.id_periodo_escolar
        WHERE a.id_avance = :id_avance AND a.id_periodo_escolar = :periodo_id
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_avance', $id_avance, PDO::PARAM_INT);
    $stmt->bindParam(':periodo_id', $periodo_id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Función para obtener datos de instrumentación
 */
function obtenerDatosInstrumentacion($conn, $id_instrumentacion, $periodo_id) {
    $query = "
        SELECT 
            i.*,
            m.nombre as nombre_profesor,
            m.apellidopaterno,
            m.apellidomaterno,
            pe.nombre_periodo,
            pe.codigoperiodo
        FROM instrumentacion i
        INNER JOIN maestros m ON i.tarjeta_profesor = m.tarjeta
        INNER JOIN periodo_escolar pe ON i.id_periodo_escolar = pe.id_periodo_escolar
        WHERE i.id_instrumentacion = :id_instrumentacion AND i.id_periodo_escolar = :periodo_id
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_instrumentacion', $id_instrumentacion, PDO::PARAM_INT);
    $stmt->bindParam(':periodo_id', $periodo_id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Función para obtener detalles de actividades de liberación docente
 */


// Funciones para otros tipos de plantillas (reportefinal, libeacademica)...
function obtenerDatosReporteFinal($conn, $id_reporte, $periodo_id) {
    // Implementar según estructura de tabla reportefinal
    return ['mensaje' => 'Función no implementada aún'];
}

function obtenerDatosLiberacionAcademica($conn, $id_liberacion, $periodo_id) {
    // Implementar según estructura de tabla libeacademica
    return ['mensaje' => 'Función no implementada aún'];
}