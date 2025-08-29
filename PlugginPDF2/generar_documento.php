<?php
//http://localhost/Inicio%20de%20sesion/PlugginPDF2/generar_documento.php?tipo=libedocente&periodo_id=4&id_tabla=1
//http://localhost/Inicio%20de%20sesion/PlugginPDF2/generar_documento.php?tipo=avance&periodo_id=4&id_tabla=1
//http://sii.tiqroo.com/PlugginPDF2/generar_documento.php?tipo=comision&periodo_id=2&id_tabla=1&id_tarjeta=10001001
require_once 'includes/db.php';
require_once 'pdf.php';
require_once 'tbs_3152/tbs_class.php';
require_once 'tbs_plugin_opentbs_1.12.0/tbs_plugin_opentbs.php';
require_once 'eleccion_tabla.php';
require_once 'datos_repositorio.php';

header('Content-Type: text/html; charset=utf-8');

// Recibir parámetros GET
$plantilla_id = $_GET['id'] ?? null;
$tipo_plantilla = $_GET['tipo'] ?? null;
$periodo_id = $_GET['periodo_id'] ?? null;
$id_tabla = $_GET['id_tabla'] ?? null;
$id_tarjeta = $_GET['id_tarjeta'] ?? null; // Nuevo parámetro opcional

// Validar parámetros obligatorios
if (!$tipo_plantilla || !$periodo_id || !$id_tabla) {
    die("Error: Los parámetros 'tipo', 'periodo_id' e 'id_tabla' son obligatorios.");
}

try {
    // Obtener el ID del tipo de plantilla basado en el nombre
    $tipo_plantilla_id = obtenerIdTipoPlantilla($conn, $tipo_plantilla);
    if (!$tipo_plantilla_id) {
        die("Error: Tipo de plantilla '$tipo_plantilla' no válido.");
    }

    // Buscar plantilla
    if (!$plantilla_id) {
        $plantilla = obtenerPlantillaActiva($conn, $tipo_plantilla_id, $periodo_id);
    } else {
        $plantilla = obtenerPlantillaPorId($conn, $plantilla_id, $tipo_plantilla_id);
    }

    if (!$plantilla) {
        die("Error: No se encontró plantilla activa para tipo: $tipo_plantilla");
    }

    $ruta_plantilla = '../Back/public/storage/plantillas/' . $plantilla['archivo'];
    if (!file_exists($ruta_plantilla)) {
        die("Error: Archivo de plantilla no encontrado: " . realpath($ruta_plantilla));
    }

    // Inicializar TBS + OpenTBS
    $TBS = new clsTinyButStrong;
    $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
    $TBS->LoadTemplate($ruta_plantilla, OPENTBS_ALREADY_UTF8);

    // Obtener datos según tipo, periodo e ID específico (incluyendo tarjeta si está disponible)
    $datos = obtenerDatosDesdeTabla($conn, $tipo_plantilla, $periodo_id, $id_tabla, $id_tarjeta);
    
    if (empty($datos)) {
        die("Error: No se encontraron datos para el ID $id_tabla en la tabla $tipo_plantilla");
    }
    
    // Procesar datos según la plantilla específica
    procesarDatosParaPlantilla($TBS, $datos, $tipo_plantilla);

    $nombreArchivo = 'documento_' . $tipo_plantilla . '_' . $id_tabla . '_' . time();
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