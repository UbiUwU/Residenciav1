<?php
/**
 * Función principal para obtener datos según el tipo de plantilla
 * 
 * @param PDO $conn Conexión a la base de datos
 * @param string $tipo_plantilla Tipo de plantilla
 * @param int $periodo_id ID del periodo escolar
 * @param int $id_tabla ID específico del registro
 * @param int|null $id_tarjeta ID de tarjeta opcional
 * @return array Datos para la plantilla
 */
function obtenerIdTipoPlantilla($conn, $nombre_tipo)
{
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
function obtenerPlantillaActiva($conn, $tipo_id, $periodo_id)
{
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
function obtenerPlantillaPorId($conn, $plantilla_id, $tipo_id)
{
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
function obtenerDatosDesdeTabla($conn, $tipo_plantilla, $periodo_id, $id_tabla, $id_tarjeta = null)
{

    
    // Validar que el tipo de plantilla es válido
    $tipos_validos = ['avance', 'instrumentacion', 'reportefinal', 'libedocente', 'libeacademica', 'comision'];

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
        case 'comision':
            return obtenerDatosComisionesMaestro($conn, $id_tabla, $periodo_id, $id_tarjeta);

        default:
            throw new Exception("Tipo de plantilla no implementado: $tipo_plantilla");
    }
    
}

function obtenerInfoPeriodo($conn, $periodo_id)
{
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
function obtenerDatosLiberacionDocente($conn, $id_liberacion, $periodo_id)
{
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

function obtenerDetallesActividades($conn, $id_liberacion)
{
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
 */function obtenerDatosAvanceProgramatico($conn, $id_avance, $periodo_id, $id_tarjeta = null) {
    $query = "
        SELECT 
            a.id_avance,
            a.clave_asignatura,
            a.tarjeta_profesor,
            a.id_periodo_escolar,
            a.fecha_creacion,
            a.fecha_ultima_actualizacion,
            a.firma_profesor,
            a.firma_jefe_carrera,
            a.requiere_firma_jefe,
            a.estado,
            a.clave_horario,
            asi.\"NombreAsignatura\" as nombre_asignatura,
            asi.\"Creditos\",
            asi.\"Satca_Teoricas\",
            asi.\"Satca_Practicas\",
            asi.\"Satca_Total\",
            m.nombre as nombre_maestro,
            m.apellidopaterno,
            m.apellidomaterno,
            d.nombre as departamento,
            d.abreviacion as departamento_abrev,
            pe.nombre_periodo,
            pe.codigoperiodo,
            pe.fecha_inicio,
            pe.fecha_fin,
            -- Información del horario
            ham.clavegrupo,
            ham.claveaula,
            ham.lunes_hi, ham.lunes_hf,
            ham.martes_hi, ham.martes_hf,
            ham.miercoles_hi, ham.miercoles_hf,
            ham.jueves_hi, ham.jueves_hf,
            ham.viernes_hi, ham.viernes_hf,
            ham.sabado_hi, ham.sabado_hf,
            -- Información de la carrera
            c.clavecarrera,
            c.nombre as nombre_carrera
        FROM avance a
        INNER JOIN asignatura asi ON a.clave_asignatura = asi.\"ClaveAsignatura\"
        INNER JOIN maestros m ON a.tarjeta_profesor = m.tarjeta
        INNER JOIN departamentos d ON m.id_departamento = d.id_departamento
        INNER JOIN periodo_escolar pe ON a.id_periodo_escolar = pe.id_periodo_escolar
        LEFT JOIN horarioasignatura_maestro ham ON a.clave_horario = ham.clavehorario
        LEFT JOIN asignatura_carrera ac ON a.clave_asignatura = ac.\"Clave_Asignatura\"
        LEFT JOIN carreras c ON ac.\"Clave_Carrera\" = c.clavecarrera
        WHERE a.id_avance = :id_avance 
        AND a.id_periodo_escolar = :periodo_id
    ";
    
    // Si se proporciona tarjeta, validar que el avance pertenece al maestro
    if ($id_tarjeta) {
        $query .= " AND a.tarjeta_profesor = :tarjeta_maestro";
    }
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_avance', $id_avance, PDO::PARAM_INT);
    $stmt->bindParam(':periodo_id', $periodo_id, PDO::PARAM_INT);
    
    if ($id_tarjeta) {
        $stmt->bindParam(':tarjeta_maestro', $id_tarjeta, PDO::PARAM_INT);
    }
    
    $stmt->execute();
    
    $avance = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($avance) {
        // Obtener detalles del avance (temas)
        $avance['detalles'] = obtenerDetallesAvance($conn, $id_avance);
        
    }
    
    return $avance;
}

/**
 * Función para obtener los detalles (temas) de un avance
 */
function obtenerSubtemasPorTema($conn) {
    $query = "
        SELECT 
            t.\"id_Tema\",
            t.\"Nombre_Tema\",
            s.\"id_Subtema\",
            s.\"Nombre_Subtema\",
            s.\"Orden\"
        FROM tema t
        LEFT JOIN subtema s ON t.\"id_Tema\" = s.\"Tema_id\"
        ORDER BY t.\"id_Tema\", s.\"Orden\"
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Organizar subtemas por tema
    $temasConSubtemas = [];
    foreach ($resultados as $fila) {
        $idTema = $fila['id_Tema'];
        
        if (!isset($temasConSubtemas[$idTema])) {
            $temasConSubtemas[$idTema] = [
                'id_Tema' => $idTema,
                'Nombre_Tema' => $fila['Nombre_Tema'] ?? '',
                'Subtemas' => []
            ];
        }
        
        if ($fila['id_Subtema']) {
            $temasConSubtemas[$idTema]['Subtemas'][] = [
                'id_Subtema' => $fila['id_Subtema'] ?? '',
                'Nombre_Subtema' => $fila['Nombre_Subtema'] ?? '',
                'Orden' => $fila['Orden'] ?? ''
            ];
        }
    }

    // Convertir a array indexado y normalizar temas sin subtemas
    $resultadoFinal = array_values($temasConSubtemas);
    foreach ($resultadoFinal as &$tema) {
        if (empty($tema['Subtemas'])) {
            $tema['Subtemas'] = [[
                'id_Subtema' => '',
                'Nombre_Subtema' => '',
                'Orden' => ''
            ]];
        }
    }

    return $resultadoFinal;
}
function obtenerSubtemas($conn, $id_tema) {
    $query = "
        SELECT 
            s.\"id_Subtema\",
            s.\"Nombre_Subtema\",
            s.\"Orden\"
        FROM subtema s
        WHERE s.\"Tema_id\" = :id_tema
        ORDER BY s.\"Orden\"
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_tema', $id_tema, PDO::PARAM_INT);
    $stmt->execute();
    $subtemas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Normalizar subtemas vacíos
    if (empty($subtemas)) {
        $subtemas[] = [
            'id_Subtema' => '',
            'Nombre_Subtema' => '',
            'Orden' => ''
        ];
    } else {
        foreach ($subtemas as &$s) {
            $s['id_Subtema'] = $s['id_Subtema'] ?? '';
            $s['Nombre_Subtema'] = $s['Nombre_Subtema'] ?? '';
            $s['Orden'] = $s['Orden'] ?? '';
        }
    }

    return $subtemas;
}
function obtenerDetallesAvance($conn, $id_avance) {
    $query = "
        SELECT 
            ad.id_avance_detalle,
            ad.id_tema,
            ad.porcentaje_aprobacion,
            ad.requiere_firma_docente,
            ad.observaciones,
            ad.created_at,
            ad.updated_at,
            t.\"Nombre_Tema\" AS nombre_tema,
            t.\"Numero\" AS numero_tema
        FROM avance_detalles ad
        LEFT JOIN tema t ON ad.id_tema = t.\"id_Tema\"
        WHERE ad.id_avance = :id_avance
        ORDER BY t.\"Numero\", ad.id_avance_detalle
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_avance', $id_avance, PDO::PARAM_INT);
    $stmt->execute();
    
    $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($detalles as &$detalle) {
        
        // --- Fechas ---
        $queryFechas = "
            SELECT 
                fecha_inicio,
                fecha_fin,
                fecha_inicio_real,
                fecha_fin_real,
                fecha_evaluacion,
                fecha_evaluacion_real
            FROM avance_detalles_fechas
            WHERE id_avance_detalle = :id_avance_detalle
        ";
        
        $stmtFechas = $conn->prepare($queryFechas);
        $stmtFechas->bindParam(':id_avance_detalle', $detalle['id_avance_detalle'], PDO::PARAM_INT);
        $stmtFechas->execute();
        $detalle['fechas'] = $stmtFechas->fetchAll(PDO::FETCH_ASSOC);

        // Normalizar fechas vacías
        if (empty($detalle['fechas'])) {
            $detalle['fecha'] = [
                'fecha_inicio' => '',
                'fecha_fin' => '',
                'fecha_inicio_real' => '',
                'fecha_fin_real' => '',
                'fecha_evaluacion' => '',
                'fecha_evaluacion_real' => ''
            ];
        } else {
            $detalle['fecha'] = $detalle['fechas'][0]; // Tomamos la primera
        }
    }
    
    return $detalles;
}


/**
 * Función para obtener datos de instrumentación
 */
function obtenerDatosInstrumentacion($conn, $id_instrumentacion, $periodo_id)
{
 
}

/**
 * Función para obtener detalles de actividades de liberación docente
 */

/**
 * Función para obtener datos de reporte final con asignaturas
 */
function obtenerDatosReporteFinal($conn, $id_reportefinal, $periodo_id, $id_tarjeta = null) {
    $query = "
        SELECT 
            rf.id_reportefinal,
            rf.tarjeta_profesor,
            rf.estado,
            m.nombre as nombre_maestro,
            m.apellidopaterno,
            m.apellidomaterno,
            d.nombre as departamento,
            d.abreviacion as departamento_abrev,
            pe.nombre_periodo,
            pe.codigoperiodo,
            pe.fecha_inicio,
            pe.fecha_fin,
            -- Datos estáticos del reporte
            ds.numero_grupos_atendidos,
            ds.numero_estudiantes,
            ds.numero_asignaturas_diferentes
        FROM reportefinal rf
        INNER JOIN maestros m ON rf.tarjeta_profesor = m.tarjeta
        INNER JOIN departamentos d ON rf.id_departamento = d.id_departamento
        INNER JOIN periodo_escolar pe ON rf.id_periodo_escolar = pe.id_periodo_escolar
        LEFT JOIN datos_estaticos_reportefinal ds ON rf.id_reportefinal = ds.id_reportefinal
        WHERE rf.id_reportefinal = :id_reportefinal 
        AND rf.id_periodo_escolar = :periodo_id
    ";
    
    // Si se proporciona tarjeta, validar que el reporte pertenece al maestro
    if ($id_tarjeta) {
        $query .= " AND rf.tarjeta_profesor = :tarjeta_maestro";
    }
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_reportefinal', $id_reportefinal, PDO::PARAM_INT);
    $stmt->bindParam(':periodo_id', $periodo_id, PDO::PARAM_INT);
    
    if ($id_tarjeta) {
        $stmt->bindParam(':tarjeta_maestro', $id_tarjeta, PDO::PARAM_INT);
    }
    
    $stmt->execute();
    
    $reporte = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($reporte) {
        // Obtener asignaturas del reporte
        $reporte['asignaturas'] = obtenerAsignaturasReporteFinal($conn, $id_reportefinal);
        
        // Calcular totales
        $reporte['totales'] = calcularTotalesReporteFinal($reporte['asignaturas']);
    }
    
    return $reporte;
}

/**
 * Función para obtener las asignaturas de un reporte final
 */
function obtenerAsignaturasReporteFinal($conn, $id_reportefinal) {
    $query = "
        SELECT 
            ra.id_reportefinal_asignatura,
            ra.clave_asignatura,
            ra.clave_carrera,
            ra.a,
            ra.b,
            ra.bco,
            ra.c,
            ra.d,
            ra.e,
            ra.f,
            ra.g,
            ra.h,
            a.\"NombreAsignatura\" as nombre_asignatura,
            c.nombre as nombre_carrera,
            c.generacion
        FROM reportefinal_asignatura ra
        INNER JOIN asignatura a ON ra.clave_asignatura = a.\"ClaveAsignatura\"
        INNER JOIN carreras c ON ra.clave_carrera = c.clavecarrera
        WHERE ra.id_reportefinal = :id_reportefinal
        ORDER BY c.nombre, a.\"NombreAsignatura\"
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_reportefinal', $id_reportefinal, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Función para calcular totales del reporte final
 */
function calcularTotalesReporteFinal($asignaturas) {
    $totales = [
        'total_a' => 0,
        'total_b' => 0,
        'total_bco' => 0,
        'total_c' => 0,
        'total_d' => 0,
        'total_e' => 0,
        'total_f' => 0,
        'total_g' => 0,
        'total_h' => 0,
        'total_asignaturas' => count($asignaturas)
    ];
    
    foreach ($asignaturas as $asignatura) {
        $totales['total_a'] += $asignatura['a'];
        $totales['total_b'] += $asignatura['b'];
        $totales['total_bco'] += $asignatura['bco'];
        $totales['total_c'] += $asignatura['c'];
        $totales['total_d'] += $asignatura['d'];
        $totales['total_e'] += $asignatura['e'];
        $totales['total_f'] += $asignatura['f'];
        $totales['total_g'] += $asignatura['g'];
        $totales['total_h'] += $asignatura['h'];
    }
    
    return $totales;
}

/**
 * Función para obtener todos los reportes finales de un maestro en un periodo
 */
function obtenerTodosReportesFinales($conn, $periodo_id, $id_tarjeta) {
    if (!$id_tarjeta) {
        throw new Exception("Error: Se requiere la tarjeta del maestro.");
    }
    
    $query = "
        SELECT 
            rf.id_reportefinal,
            rf.estado,
            COUNT(ra.id_reportefinal_asignatura) as total_asignaturas,
            MIN(ra.fecha_registro) as fecha_inicio,
            MAX(ra.fecha_actualizacion) as fecha_actualizacion
        FROM reportefinal rf
        LEFT JOIN reportefinal_asignatura ra ON rf.id_reportefinal = ra.id_reportefinal
        WHERE rf.id_periodo_escolar = :periodo_id
        AND rf.tarjeta_profesor = :tarjeta_maestro
        GROUP BY rf.id_reportefinal, rf.estado
        ORDER BY rf.estado, MIN(ra.fecha_registro)
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':periodo_id', $periodo_id, PDO::PARAM_INT);
    $stmt->bindParam(':tarjeta_maestro', $id_tarjeta, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
/**
 * Función para obtener datos de liberación académica
 */
function obtenerDatosLiberacionAcademica($conn, $id_liberacion, $periodo_id) {
    $query = "
        SELECT 
            la.id_liberacion,
            la.fecha_liberacion,
            la.otorga_liberacion,
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
            COUNT(lad.id_detalle) as total_actividades,
            SUM(CASE WHEN lad.si = true THEN 1 ELSE 0 END) as actividades_si,
            SUM(CASE WHEN lad.no = true THEN 1 ELSE 0 END) as actividades_no,
            SUM(CASE WHEN lad.na = true THEN 1 ELSE 0 END) as actividades_na
        FROM liberacion_academica la
        INNER JOIN maestros m ON la.tarjeta_maestro = m.tarjeta
        INNER JOIN departamentos d ON la.id_departamento = d.id_departamento
        INNER JOIN periodo_escolar pe ON la.id_periodo_escolar = pe.id_periodo_escolar
        LEFT JOIN liberacion_academica_detalles lad ON la.id_liberacion = lad.id_liberacion
        WHERE la.id_liberacion = :id_liberacion AND la.id_periodo_escolar = :periodo_id
        GROUP BY la.id_liberacion, m.tarjeta, d.nombre, d.abreviacion, pe.nombre_periodo, pe.fecha_inicio, pe.fecha_fin, pe.codigoperiodo
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_liberacion', $id_liberacion, PDO::PARAM_INT);
    $stmt->bindParam(':periodo_id', $periodo_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $liberacion = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Obtener detalles de las actividades académicas
    if ($liberacion) {
        $liberacion['actividades'] = obtenerDetallesActividadesAcademicas($conn, $id_liberacion);
    }
    
    return $liberacion;
}

/**
 * Función para obtener detalles de las actividades de liberación académica
 */
function obtenerDetallesActividadesAcademicas($conn, $id_liberacion) {
    $query = "
        SELECT 
            numero_actividad,
            descripcion_actividad,
            si,
            no,
            na
        FROM liberacion_academica_detalles
        WHERE id_liberacion = :id_liberacion
        ORDER BY numero_actividad
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_liberacion', $id_liberacion, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerDatosComisionesMaestro($conn, $id_comision, $periodo_id, $id_tarjeta) {
    // Validar que se proporcionó la tarjeta del maestro
    if (!$id_tarjeta) {
        throw new Exception("Error: Se requiere la tarjeta del maestro para obtener comisiones.");
    }
    
    $query = "
        SELECT 
            c.id_comision,
            c.folio,
            c.nombre_evento,
            c.estado,
            c.remitente_nombre,
            c.remitente_puesto,
            c.lugar,
            c.motivo,
            c.tipo_comision,
            c.permiso_constancia,
            te.nombre as tipo_evento,
            pe.nombre_periodo,
            pe.codigoperiodo,
            pe.fecha_inicio,
            pe.fecha_fin,
            m.tarjeta,
            m.nombre as nombre_maestro,
            m.apellidopaterno,
            m.apellidomaterno,
            d.nombre as departamento,
            d.abreviacion as departamento_abrev
        FROM comision c
        INNER JOIN comision_maestro cm ON c.id_comision = cm.id_comision
        INNER JOIN maestros m ON cm.tarjeta_maestro = m.tarjeta
        INNER JOIN departamentos d ON m.id_departamento = d.id_departamento
        INNER JOIN tipo_evento te ON c.id_tipo_evento = te.id_tipo_evento
        INNER JOIN periodo_escolar pe ON c.id_periodo_escolar = pe.id_periodo_escolar
        WHERE c.id_comision = :id_comision 
        AND c.id_periodo_escolar = :periodo_id
        AND cm.tarjeta_maestro = :id_tarjeta
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_comision', $id_comision, PDO::PARAM_INT);
    $stmt->bindParam(':periodo_id', $periodo_id, PDO::PARAM_INT);
    $stmt->bindParam(':id_tarjeta', $id_tarjeta, PDO::PARAM_INT);
    $stmt->execute();
    
    $comision = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($comision) {
        // Obtener fechas de la comisión
        $comision['fechas'] = obtenerFechasComision($conn, $id_comision);
        
        // Calcular duración total
        $comision['duracion_total'] = calcularDuracionTotal($comision['fechas']);
    }
    
    return $comision;
}

/**
 * Función para obtener las fechas de una comisión
 */
function obtenerFechasComision($conn, $id_comision) {
    $query = "
        SELECT 
            fecha,
            hora_inicio,
            hora_fin,
            duracion,
            observaciones
        FROM comision_fecha
        WHERE id_comision = :id_comision
        ORDER BY fecha
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_comision', $id_comision, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Función para calcular la duración total de una comisión en horas
 */
function calcularDuracionTotal($fechas) {
    $duracionTotal = 0;
    
    foreach ($fechas as $fecha) {
        if (!empty($fecha['duracion'])) {
            // Convertir intervalo PostgreSQL a horas
            $duracion = $fecha['duracion'];
            $horas = convertirIntervaloAHoras($duracion);
            $duracionTotal += $horas;
        } elseif (!empty($fecha['hora_inicio']) && !empty($fecha['hora_fin'])) {
            // Calcular diferencia entre horas
            $inicio = new DateTime($fecha['hora_inicio']);
            $fin = new DateTime($fecha['hora_fin']);
            $diferencia = $inicio->diff($fin);
            $horas = $diferencia->h + ($diferencia->i / 60) + ($diferencia->s / 3600);
            $duracionTotal += $horas;
        }
    }
    
    return round($duracionTotal, 2);
}

/**
 * Función para convertir intervalo PostgreSQL a horas
 */
function convertirIntervaloAHoras($intervalo) {
    // Formato de intervalo PostgreSQL: 'HH:MM:SS' o 'DD days HH:MM:SS'
    $horas = 0;
    
    if (preg_match('/(?:(\d+) days? )?(\d+):(\d+):(\d+)/', $intervalo, $matches)) {
        $dias = isset($matches[1]) ? (int)$matches[1] : 0;
        $horas_part = isset($matches[2]) ? (int)$matches[2] : 0;
        $minutos = isset($matches[3]) ? (int)$matches[3] : 0;
        $segundos = isset($matches[4]) ? (int)$matches[4] : 0;
        
        $horas = ($dias * 24) + $horas_part + ($minutos / 60) + ($segundos / 3600);
    }
    
    return $horas;
}

/**
 * Función para obtener todas las comisiones de un maestro en un periodo
 */
function obtenerTodasComisionesMaestro($conn, $periodo_id, $id_tarjeta) {
    if (!$id_tarjeta) {
        throw new Exception("Error: Se requiere la tarjeta del maestro.");
    }
    
    $query = "
        SELECT 
            c.id_comision,
            c.folio,
            c.nombre_evento,
            c.estado,
            c.tipo_comision,
            te.nombre as tipo_evento,
            MIN(cf.fecha) as fecha_inicio,
            MAX(cf.fecha) as fecha_fin
        FROM comision c
        INNER JOIN comision_maestro cm ON c.id_comision = cm.id_comision
        INNER JOIN tipo_evento te ON c.id_tipo_evento = te.id_tipo_evento
        LEFT JOIN comision_fecha cf ON c.id_comision = cf.id_comision
        WHERE c.id_periodo_escolar = :periodo_id
        AND cm.tarjeta_maestro = :id_tarjeta
        GROUP BY c.id_comision, te.nombre
        ORDER BY MIN(cf.fecha)
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':periodo_id', $periodo_id, PDO::PARAM_INT);
    $stmt->bindParam(':id_tarjeta', $id_tarjeta, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}