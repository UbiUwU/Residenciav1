<?php
/**
 * data_repository.php
 * 
 * Aquí se procesan los datos para los reportes según tipo y tarjeta.
 * 
 * Aqui se debe poner la informacion para la creacion de los pdfs
 */

function obtenerDatosDesdeFuncion(PDO $conn, string $tipo_plantilla, ?int $tarjeta): array
{
    if (!$tarjeta) {
        throw new Exception("Se requiere la tarjeta para obtener datos.");
    }

    switch ($tipo_plantilla) {
        case 'reporte_final':
            return obtenerDatosReporteFinal($conn, $tarjeta);
        case 'avance':
            return obtenerDatosAvance($conn, $tarjeta);
        // Aquí puedes agregar más tipos y funciones
        default:
            throw new Exception("Tipo de plantilla no soportado: $tipo_plantilla");
    }
}

/**
 * Obtener datos para reporte final.
 * Llama a la función SQL y procesa los resultados con cálculos.
 */
function obtenerDatosReporteFinal(PDO $conn, int $tarjeta): array
{
    // Obtener los datos completos del maestro
    $sqlMaestro = "SELECT nombre, apellidopaterno, apellidomaterno FROM public.get_maestro_by_tarjeta(:tarjeta) LIMIT 1";
    $stmtMaestro = $conn->prepare($sqlMaestro);
    $stmtMaestro->bindParam(':tarjeta', $tarjeta, PDO::PARAM_INT);
    $stmtMaestro->execute();
    $maestroRow = $stmtMaestro->fetch(PDO::FETCH_ASSOC);

    if ($maestroRow) {
        $nombreCompleto = trim($maestroRow['nombre'] . ' ' . $maestroRow['apellidopaterno'] . ' ' . $maestroRow['apellidomaterno']);
    } else {
        $nombreCompleto = 'Desconocido';
    }
    

    // Obtener datos de calificaciones
    $sql = "SELECT public.get_detalle_grupos_calificacion_por_carrera(:tarjeta) AS resultado";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':tarjeta', $tarjeta, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || !$row['resultado']) {
        return [];
    }

    $jsonData = $row['resultado'];
    $asignaturas = json_decode($jsonData, true);

    if (!is_array($asignaturas)) {
        return [];
    }

    // Variables para cálculo global
    $totalGrupos = 0;
    $totalAlumnos = 0;
    $totalAsignaturas = count($asignaturas);
    $totalAprobadosOrdinario = 0;
    $totalAprobadosComplementario = 0;
    $totalAprobadosExtraordinario = 0;

    // Procesar cada asignatura
    foreach ($asignaturas as &$asignatura) {
        $gruposAsignatura = 0;
        $alumnosAsignatura = 0;

        $alumnosOrdinario = 0;
        $alumnosComplementario = 0;
        $aprobadosOrdinario = 0;
        $aprobadosComplementario = 0;
        $reprobados = 0;
        $sumaAprobados = 0;
        $cuentaAprobados = 0;

        // Extraer periodo solo una vez
        if (!empty($asignatura['aulas_grupos_periodos'])) {
            $asignatura['periodo'] = $asignatura['aulas_grupos_periodos'][0]['periodo'] ?? null;
        } else {
            $asignatura['periodo'] = null;
        }

        
        foreach ($asignatura['aulas_grupos_periodos'] as &$agp) {
            $gruposAsignatura++;
            $resumen['periodo'] = $datos['asignaturas'][0]['aulas_grupos_periodos'][0]['periodo'] ?? 'Sin periodo';
            $agp['periodo'] = $asignatura['periodo'];
            $agp['resumen'] = [
                'periodo' => $agp['periodo'],
                'asignatura' => $asignatura['nombre'],
            ];

            foreach ($agp['carreras'] as &$carrera) {
                foreach ($carrera['alumnos'] as &$alumno) {
                    $alumnosAsignatura++;

                    foreach ($alumno['calificaciones'] as $calif) {
                        $cal = floatval($calif['calificacion']);
                        $tipo = $calif['tipo_evaluacion'];

                        if ($tipo === 'Ordinario') {
                            $alumnosOrdinario++;
                            if ($cal >= 70) {
                                $aprobadosOrdinario++;
                                $sumaAprobados += $cal;
                                $cuentaAprobados++;
                            } else {
                                $reprobados++;
                            }
                        } elseif ($tipo === 'Complementario') {
                            $alumnosComplementario++;
                            if ($cal >= 70) {
                                $aprobadosComplementario++;
                                $sumaAprobados += $cal;
                                $cuentaAprobados++;
                            } else {
                                $reprobados++;
                            }
                        } elseif ($tipo === 'Extraordinario') {
                            if ($cal >= 70) {
                                $totalAprobadosExtraordinario++;
                                $sumaAprobados += $cal;
                                $cuentaAprobados++;
                            } else {
                                $reprobados++;
                            }
                        }
                    }
                }
            }
        }

        // Porcentajes por asignatura
        $totalEvaluados = $alumnosOrdinario + $alumnosComplementario;
        $porcAprobOrdinario = $alumnosOrdinario > 0 ? ($aprobadosOrdinario / $alumnosOrdinario) * 100 : 0;
        $porcAprobComplementario = $alumnosComplementario > 0 ? ($aprobadosComplementario / $alumnosComplementario) * 100 : 0;
        $porcReprobados = $totalEvaluados > 0 ? ($reprobados / $totalEvaluados) * 100 : 0;
        $promAprobados = $cuentaAprobados > 0 ? $sumaAprobados / $cuentaAprobados : 0;

        // Guardar resumen por asignatura
        $asignatura['resumen'] = [
            'total_alumnos_ordinario' => $alumnosOrdinario,
            'total_alumnos_complementario' => $alumnosComplementario,
            'total_aprobados_ordinario' => $aprobadosOrdinario,
            'total_aprobados_complementario' => $aprobadosComplementario,
            'porcentaje_aprobados_ordinario' => round($porcAprobOrdinario, 2),
            'porcentaje_aprobados_complementario' => round($porcAprobComplementario, 2),
            'total_reprobados' => $reprobados,
            'porcentaje_reprobados' => round($porcReprobados, 2),
            'promedio_aprobados' => round($promAprobados, 2),
        ];

        // Guardar totales por asignatura
        $asignatura['total_grupos'] = $gruposAsignatura;
        $asignatura['total_alumnos'] = $alumnosAsignatura;

        // Sumar globales
        $totalGrupos += $gruposAsignatura;
        $totalAlumnos += $alumnosAsignatura;
        $totalAprobadosOrdinario += $aprobadosOrdinario;
        $totalAprobadosComplementario += $aprobadosComplementario;
    }

    unset($asignatura, $agp, $carrera, $alumno);

    // Resumen global
    $resumen = [
        'total_asignaturas' => $totalAsignaturas,
        'total_grupos' => $totalGrupos,
        'total_alumnos' => $totalAlumnos,
        'total_aprobados_ordinario' => $totalAprobadosOrdinario,
        'total_aprobados_complementario' => $totalAprobadosComplementario,
        'total_aprobados_extraordinario' => $totalAprobadosExtraordinario,
        'porcentaje_aprobados_ordinario' => $totalAlumnos > 0 ? round(($totalAprobadosOrdinario / $totalAlumnos) * 100, 2) : 0,
        'porcentaje_aprobados_complementario' => $totalAlumnos > 0 ? round(($totalAprobadosComplementario / $totalAlumnos) * 100, 2) : 0,
        'porcentaje_aprobados_extraordinario' => $totalAlumnos > 0 ? round(($totalAprobadosExtraordinario / $totalAlumnos) * 100, 2) : 0,
        'porcentaje_reprobados' => $totalAlumnos > 0 ? round((($totalAlumnos - ($totalAprobadosOrdinario + $totalAprobadosComplementario + $totalAprobadosExtraordinario)) / $totalAlumnos) * 100, 2) : 0,
        'promedio_aprobados' => $totalAprobadosOrdinario + $totalAprobadosComplementario > 0 ? round(($sumaAprobados / ($totalAprobadosOrdinario + $totalAprobadosComplementario)), 2) : 0,
        'periodo' => $asignaturas[0]['aulas_grupos_periodos'][0]['periodo'] ?? 'Sin periodo',
    ];

    return [
        'resumen' => $resumen,
        'maestro_nombre' => $nombreCompleto,
        'asignaturas' => $asignaturas,
    ];
    
}


/**
 * Ejemplo básico para obtener datos avance (puedes adaptar)
 */
function obtenerDatosAvance(PDO $conn, int $tarjeta): array
{
    // Aquí llamarías otra función SQL similar y procesas
    // Por ahora devolvemos arreglo vacío para ejemplo
    return [];
}
