<?php
// Incluir las dependencias necesarias
require_once 'includes/db.php';
require_once 'pdf.php';
require_once 'tbs_3152/tbs_class.php';
require_once 'tbs_plugin_opentbs_1.12.0/tbs_plugin_opentbs.php';
require_once 'data_repository.php';
header('Content-Type: text/html; charset=utf-8');


// Obtener el ID de la plantilla desde el GET
$plantilla_id = $_GET['id'] ?? null;
$tipo_plantilla = $_GET['tipo'] ?? null;

if (!$plantilla_id || !$tipo_plantilla) {
    die("Error: Se requiere tanto el ID como el tipo de plantilla");
}

try {
    // Consulta con tipo_plantilla
    $plantilla = getOne(
        "SELECT * FROM plantillas WHERE id = ? AND tipo_plantilla = ?",
        [$plantilla_id, $tipo_plantilla]
    );

    if (!$plantilla) {
        die("Error: No se encontró la plantilla con ID $plantilla_id y tipo $tipo_plantilla");
    }
    // Verificar y construir la ruta correcta
    $ruta_plantilla = 'doc/' . $plantilla['archivo'];
    
    if (!file_exists($ruta_plantilla)) {
        die("Error: El archivo de plantilla no existe en: " . realpath($ruta_plantilla));
    }

    // 2. Cargar la plantilla DOCX
    $TBS = new clsTinyButStrong;
    $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
    $TBS->LoadTemplate($ruta_plantilla,OPENTBS_ALREADY_UTF8);

    // 3. Reemplazar los placeholders con datos del repositorio
    reemplazarPlaceholders($TBS, $conn);

    // 4. Guardar el DOCX temporal
    $nombreArchivo = 'ticket_' . $plantilla_id . '_' . time();
    $docxPath = "doc/{$nombreArchivo}.docx";
    $TBS->Show(OPENTBS_FILE, $docxPath);

    // 5. Convertir a PDF
    $pdf_file = createPDF($nombreArchivo);
    
    if (!$pdf_file) {
        die("Error al convertir el documento a PDF");
    }

    // 6. Enviar el PDF al navegador
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=ticket_" . htmlspecialchars($plantilla['nombre']) . ".pdf");
    readfile($pdf_file);

    // 7. Opcional: Eliminar archivos temporales
    deleteFiles($nombreArchivo);

} catch (Exception $e) {
    die("Error al generar el documento: " . $e->getMessage());
}