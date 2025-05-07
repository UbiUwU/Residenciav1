<?php
include_once('pdf.php');
include_once('includes/db.php');
include_once('tbs_3152/tbs_class.php');
include_once('tbs_plugin_opentbs_1.12.0/tbs_plugin_opentbs.php');
include_once('data_repository.php'); // Importar el repositorio de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['plantilla_id'])) {
    $plantilla_id = $_POST['plantilla_id'];
    error_log("ID de la plantilla recibida: " . $plantilla_id);

    // Obtener la plantilla desde la base de datos
    $stmt = $conn->prepare("SELECT * FROM plantillas WHERE id = :id");
    $stmt->bindParam(':id', $plantilla_id);
    $stmt->execute();
    $plantilla = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($plantilla) {
        error_log("Plantilla encontrada: " . print_r($plantilla, true));

        // Cargar la plantilla de Word
        $TBS = new clsTinyButStrong;
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
        $TBS->LoadTemplate($plantilla['archivo']);

        // Reemplazar placeholders con los datos obtenidos del repositorio
        reemplazarPlaceholders($TBS, $conn);

        // Guardar el archivo generado
        $output_file_name = 'ticket.docx';
        $ruta_guardado = "doc/" . $output_file_name;
        $TBS->Show(OPENTBS_FILE, $ruta_guardado);

        if (file_exists($ruta_guardado)) {
            error_log("Archivo .docx creado correctamente: " . $ruta_guardado);
            echo "Archivo .docx creado correctamente: $ruta_guardado<br>";

            // Convertir a PDF
            $pdf_file = createPDF('ticket');

            if ($pdf_file) {
                error_log("Archivo PDF creado correctamente: " . $pdf_file);
                echo "Archivo PDF creado correctamente: $pdf_file<br>";

                header("Content-Type: application/octet-stream");
                header("Content-Disposition: attachment; filename=" . basename($pdf_file));
                readfile($pdf_file);
                exit();
            } else {
                error_log("Error al convertir el archivo a PDF.");
                echo "Error al convertir el archivo a PDF.<br>";
            }
        } else {
            error_log("Error al crear el archivo .docx.");
            echo "Error al crear el archivo .docx.<br>";
        }
    } else {
        error_log("Plantilla no encontrada.");
        echo "Plantilla no encontrada.<br>";
    }
} else {
    error_log("Acceso no autorizado.");
    echo "Acceso no autorizado.<br>";
}
?>
