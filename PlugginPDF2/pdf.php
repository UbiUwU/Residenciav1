<?php
// Función para convertir un archivo de Word a PDF
function createPDF($filename) {
    $docx_path = "doc/{$filename}.docx";
    $pdf_path = "pdf/{$filename}.pdf";

    if (!file_exists($docx_path)) {
        error_log("El archivo .docx no existe: $docx_path");
        return null;
    }

    if (!is_dir('pdf/')) {
        mkdir('pdf/', 0777, true);
    }

    $command = "soffice --headless --convert-to pdf \"$docx_path\" --outdir pdf/";
    $output = shell_exec($command . " 2>&1");
    error_log("Conversión a PDF salida: $output");

    return file_exists($pdf_path) ? $pdf_path : null;
}

// Eliminar archivos temporales
function deleteFiles($filename) {
    foreach (["doc/{$filename}.docx", "pdf/{$filename}.pdf"] as $file) {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
