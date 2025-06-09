<?php
// download2.php

// Permitir CORS para desarrollo (ajusta según dominio en producción)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Responder OPTIONS para CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Leer JSON enviado desde fetch
$rawJson = file_get_contents("php://input");
$payload  = json_decode($rawJson, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo "JSON inválido";
    exit;
}

$tipo  = $payload['tipo'] ?? 'reporte_final';
$datos = $payload['datos'] ?? [];

// Validar datos mínimo
if (empty($datos)) {
    http_response_code(400);
    echo "Datos vacíos";
    exit;
}

// Incluir TinyButStrong y OpenTBS
require_once 'tbs_3152/tbs_class.php';
require_once 'tbs_plugin_opentbs_1.12.0/tbs_plugin_opentbs.php';

// Crear instancia TBS
$TBS = new clsTinyButStrong();
$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

$template = 'templates/Contenido.docx';
if (!file_exists($template)) {
    http_response_code(500);
    echo "No se encontró la plantilla: $template";
    exit;
}

// Cargar plantilla
$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

// Aquí haces los merge de datos según estructura plantilla
// 1. Merge de asignaturas
$TBS->MergeBlock('asignaturas', $datos);

// 2. Preprocesar todas las carreras
$allCarreras = [];
foreach ($datos as $idxA => $asign) {
    if (!empty($asign['aulas_grupos_periodos'])) {
        foreach ($asign['aulas_grupos_periodos'] as $agp) {
            if (!empty($agp['carreras'])) {
                foreach ($agp['carreras'] as $carr) {
                    $carr['TBS:asignaturas'] = $idxA; // <-- asigna a cuál bloque_asignatura pertenece
                    $allCarreras[] = $carr;
                }
            }
        }
    }
}
$TBS->MergeBlock('carreras', $allCarreras);


$TBS->MergeField('fecha_emision', date('Y-m-d'));
$TBS->MergeField('hora_emision', date('H:i'));

// Crear carpetas si no existen
if (!file_exists('doc')) mkdir('doc', 0777, true);
if (!file_exists('pdf')) mkdir('pdf', 0777, true);

// Generar nombre único para archivos
$nombreArchivo = 'reporte_' . time() . '_' . bin2hex(random_bytes(4));

// Ruta archivo DOCX
$docxPath = "doc/{$nombreArchivo}.docx";

// Guardar DOCX en disco
$TBS->Show(OPENTBS_FILE, $docxPath);

// Función para convertir DOCX a PDF con LibreOffice CLI
function createPDF($nombreArchivo) {
    $input = "doc/{$nombreArchivo}.docx";
    $outputDir = "pdf/";

    // Comando para convertir DOCX a PDF (ajustar según SO)
    $cmd = "soffice --headless --convert-to pdf --outdir " . escapeshellarg($outputDir) . " " . escapeshellarg($input);

    exec($cmd, $output, $return_var);

    if ($return_var !== 0) {
        return false;
    }

    $pdfPath = $outputDir . $nombreArchivo . '.pdf';
    return file_exists($pdfPath) ? $pdfPath : false;
}

function deleteFiles($nombreArchivo) {
    @unlink("doc/{$nombreArchivo}.docx");
    @unlink("pdf/{$nombreArchivo}.pdf");
}

// Convertir a PDF
$pdf_file = createPDF($nombreArchivo);

if (!$pdf_file) {
    http_response_code(500);
    echo "Error al convertir DOCX a PDF. Verifica que LibreOffice esté instalado y accesible.";
    // Puedes borrar DOCX para limpiar
    @unlink($docxPath);
    exit;
}

// Enviar PDF para descarga
header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=reporte_final.pdf");
header('Content-Length: ' . filesize($pdf_file));

readfile($pdf_file);

// Limpiar archivos temporales
deleteFiles($nombreArchivo);
exit;
?>
