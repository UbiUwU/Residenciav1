<?php
require_once 'includes/db.php';
require_once 'pdf.php';
require_once 'tbs_3152/tbs_class.php';
require_once 'tbs_plugin_opentbs_1.12.0/tbs_plugin_opentbs.php';
require_once 'data_repository.php'; 

header('Content-Type: text/html; charset=utf-8');

// Recibir parámetros GET
$plantilla_id = $_GET['id'] ?? null;
$tipo_plantilla = $_GET['tipo'] ?? null;
$tarjeta = isset($_GET['tarjeta']) ? (int) $_GET['tarjeta'] : null;

if (!$tipo_plantilla) {
    die("Error: El parámetro 'tipo' es obligatorio.");
}

if (!$tarjeta) {
    die("Error: El parámetro 'tarjeta' es obligatorio.");
}

try {
    // Buscar plantilla: si no hay id, buscar última del tipo
    if (!$plantilla_id) {
        $plantilla = getOne(
            "SELECT * FROM plantillas WHERE tipo = ? AND estado = 'activo' ORDER BY id DESC LIMIT 1",
            [$tipo_plantilla]
        );

        if (!$plantilla) {
            die("Error: No se encontró plantilla activa para tipo: $tipo_plantilla");
        }
    } else {
        $plantilla = getOne(
            "SELECT * FROM plantillas WHERE id = ? AND tipo = ? AND estado = 1",
            [$plantilla_id, $tipo_plantilla]
        );
        if (!$plantilla) {
            die("Error: No se encontró la plantilla con ID $plantilla_id y tipo $tipo_plantilla");
        }
    }

    $ruta_plantilla = 'docx/' . $plantilla['archivo'];
    if (!file_exists($ruta_plantilla)) {
        die("Error: Archivo de plantilla no encontrado en: " . realpath($ruta_plantilla));
    }

    // Inicializar TBS + OpenTBS
    $TBS = new clsTinyButStrong;
    $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
    $TBS->LoadTemplate($ruta_plantilla, OPENTBS_ALREADY_UTF8);

    // Obtener datos según tipo y tarjeta
    $datos = obtenerDatosDesdeFuncion($conn, $tipo_plantilla, $tarjeta);


    if (!empty($datos)) {
    $TBS->MergeBlock('asignaturas', $datos['asignaturas'] ?? []);
    $TBS->MergeField('resumen', $datos['resumen'] ?? []);
    $TBS->MergeField('maestro_nombre', $datos['maestro_nombre'] ?? '');  // <--- Aquí
}


    $nombreArchivo = 'documento_' . $plantilla['id'] . '_' . time();
    $docxPath = "doc/{$nombreArchivo}.docx";

    $TBS->Show(OPENTBS_FILE, $docxPath);

    $pdf_file = createPDF($nombreArchivo);
    if (!$pdf_file) {
        die("Error al convertir el documento a PDF");
    }

    // Enviar al navegador para descarga
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=" . basename($pdf_file));
    readfile($pdf_file);

    // Limpiar archivos temporales
    deleteFiles($nombreArchivo);

} catch (Exception $e) {
    die("Error al generar el documento: " . $e->getMessage());
}
