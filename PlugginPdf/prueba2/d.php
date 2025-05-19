<?php
// includes/pdf.php

// Función para convertir un archivo de Word a PDF
function createPDF($variabledef) {
    // Ruta del archivo de Word
    $docx_file = "doc/" . $variabledef . ".docx";

    // Verificar si el archivo de Word existe
    if (!file_exists($docx_file)) {
        echo "El archivo .docx no existe: $docx_file<br>";
        return null;
    }

    // Ruta de salida para el PDF
    if (!file_exists('pdf/')) {
        mkdir('pdf/', 0777, true);
    }
    $pdf_file = "pdf/" . $variabledef . ".pdf";

    // Comando para convertir el archivo usando LibreOffice
    $command = "soffice --headless --convert-to pdf $docx_file --outdir pdf/";

    // Depuración: Mostrar el comando que se ejecutará
    echo "Comando ejecutado: $command<br>";

    // Ejecutar el comando usando shell_exec
    $output = shell_exec($command . " 2>&1");

    // Depuración: Mostrar salida del comando
    echo "Salida del comando: <pre>$output</pre>";

    // Verificar si el archivo PDF se creó correctamente
    if (file_exists($pdf_file)) {
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
        "doc/" . $variabledef . ".docx",
        "pdf/" . $variabledef . ".pdf"
    ];

    foreach ($files as $file) {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
?>