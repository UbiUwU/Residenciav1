<?php
// includes/pdf.php

// Función para convertir un archivo de Word a PDF
function createPDF($variabledef) {
    // Ruta del archivo de Word (sube un nivel desde "includes" a la raíz y luego entra a "doc")
    $docx_file = __DIR__ . "../doc/" . $variabledef . "ticket.docx";

    // Verificar si el archivo de Word existe
    if (!file_exists($docx_file)) {
        echo "El archivo .docx no existe: $docx_file<br>";
        return null;
    }

    // Ruta de salida para el PDF (sube un nivel desde "includes" a la raíz y luego entra a "pdf")
    if (!file_exists(__DIR__ . "/../pdf/")) {
        mkdir(__DIR__ . "/../pdf/", 0777, true);
    }
    $pdf_file = __DIR__ . "/../pdf/" . $variabledef . ".pdf";

    // Comando para convertir el archivo usando LibreOffice
    $command = "soffice --headless --convert-to pdf $docx_file --outdir " . __DIR__ . "/../pdf/";

    // Ejecutar el comando
    exec($command, $output, $return);

    if ($return == 0) {
        echo "Archivo convertido a PDF correctamente: $pdf_file<br>";
        return $pdf_file;
    } else {
        echo "Error al convertir el archivo a PDF.<br>";
        return null;
    }
}

// Función para eliminar archivos temporales
function deleteFiles($variabledef) {
    $files = [
        __DIR__ . "/../doc/" . $variabledef . ".docx",
        __DIR__ . "/../pdf/" . $variabledef . ".pdf"
    ];

    foreach ($files as $file) {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
?>