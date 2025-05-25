<?php
include_once('includes/db.php'); // Conexión a la base de datos

// Configuración: Cambia esta clave para probar con diferentes asignaturas
$CLAVE_ASIGNATURA = 'MAT101'; // Puedes cambiar este valor para probar con otra asignatura

// ===================== ASIGNATURA SELECCIONADA =====================
function obtenerAsignaturaPorClave(PDO $conn, string $clave): array
{
    try {
        $stmt = $conn->prepare("SELECT * FROM asignatura WHERE \"ClaveAsignatura\" = :clave");
        $stmt->bindParam(':clave', $clave, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) {
        error_log("Error al obtener asignatura: " . $e->getMessage());
        return [];
    }
}
function procesarAsignatura($TBS, PDO $conn, string $clave): void
{
    $asignatura = obtenerAsignaturaPorClave($conn, $clave);
    if ($asignatura) {
        $TBS->MergeField('asignatura.ClaveAsignatura', $asignatura['ClaveAsignatura']);
        $TBS->MergeField('asignatura.NombreAsignatura', $asignatura['NombreAsignatura']);
        $TBS->MergeField('asignatura.Creditos', $asignatura['Creditos']);
        $TBS->MergeField('asignatura.Satca_Practicas', $asignatura['Satca_Practicas']);
        $TBS->MergeField('asignatura.Satca_Teoricas', $asignatura['Satca_Teoricas']);
        $TBS->MergeField('asignatura.Satca_Total', $asignatura['Satca_Total']);
    }
}

// ===================== PRESENTACIÓN DE LA ASIGNATURA =====================
function obtenerPresentacionAsignatura(PDO $conn, string $clave): array
{
    try {
        $stmt = $conn->prepare("SELECT * FROM presentacion WHERE \"Clave_Asignatura\" = :clave");
        $stmt->bindParam(':clave', $clave, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) {
        error_log("Error al obtener la presentación de la asignatura: " . $e->getMessage());
        return [];
    }
}

function procesarPresentacion($TBS, PDO $conn, string $clave): void
{
    $presentacion = obtenerPresentacionAsignatura($conn, $clave);
    if ($presentacion) {
        $TBS->MergeField('presentacion.Caracterizacion', $presentacion['Caracterizacion']);
        $TBS->MergeField('presentacion.Intencion_didactica', $presentacion['Intencion_didactica']);
    }
}


function obtenerTemasPorAsignatura(PDO $conn, string $clave): array
{
    try {
        $stmt = $conn->prepare("
            SELECT t.*, s.\"id_Subtema\", s.\"Nombre_Subtema\" 
            FROM tema t
            LEFT JOIN subtema s ON t.\"id_Tema\" = s.\"Tema_id\"
            WHERE t.\"Clave_Asignatura\" = :clave
            ORDER BY t.\"Numero\", s.\"id_Subtema\"
        ");
        $stmt->bindParam(':clave', $clave, PDO::PARAM_STR);
        $stmt->execute();

        // Agrupar temas y subtemas
        $temas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $temaId = $row['id_Tema'];

            if (!isset($temas[$temaId])) {
                $temas[$temaId] = [
                    'Numero' => $row['Numero'],
                    'Nombre_Tema' => $row['Nombre_Tema'],
                    'Subtemas' => [],
                    // Puedes agregar aquí más campos como competencias, actividades, etc.
                ];
            }

            if (!empty($row['id_Subtema'])) {
                $temas[$temaId]['Subtemas'][] = [
                    'NumeroCompleto' => $row['Numero'] . '.' . (count($temas[$temaId]['Subtemas']) + 1),
                    'Nombre_Subtema' => $row['Nombre_Subtema']
                ];
            }

        }

        return array_values($temas);
    } catch (PDOException $e) {
        error_log("Error al obtener temas y subtemas: " . $e->getMessage());
        return [];
    }
}

function procesarTemasYSubtemas($TBS, PDO $conn, string $clave): void
{
    $temas = obtenerTemasPorAsignatura($conn, $clave);
    // Bloque padre
    $TBS->MergeBlock('bloque.tema', $temas);

    // Bloque hijo, uno por cada tema
    foreach ($temas as $i => $tema) {
        if (!empty($tema['Subtemas'])) {
            // Importante: usar la misma clave 'Subtemas'
            $TBS->MergeBlock("bloque.tema[$i].Subtemas", $tema['Subtemas']);
        }
    }
}

// 1. Genera un array donde cada elemento es un bloque de texto
function generarBloquesTemas(PDO $conn, string $clave): array {
    $temas  = obtenerTemasPorAsignatura($conn, $clave);
    $bloques = [];

    foreach ($temas as $tema) {
        // Construye el texto de subtemas
        $texto  = "{$tema['Numero']}: {$tema['Nombre_Tema']}\r\n";
        foreach ($tema['Subtemas'] as $sub) {
            $texto .= "   • {$sub['NumeroCompleto']}: {$sub['Nombre_Subtema']}\r\n";
        }
        $bloques[] = [
            'Numero'     => $tema['Numero'],   // <-- Aquí capturas el número
            'TextoTema'  => $texto,
        ];
    }
    return $bloques;
}

function procesarBloquesTemasTexto($TBS, PDO $conn, string $clave): void {
    $bloques = generarBloquesTemas($conn, $clave);
    $TBS->MergeBlock('bloque_texto_tema', $bloques);
}



// ===================== FUNCIÓN GENERAL PARA LLAMAR TODAS =====================
function reemplazarPlaceholders($TBS, PDO $conn, string $clave = 'SIS-101'): void
{
    $TBS->MergeField('fecha', date("d/m/Y"));
    $TBS->MergeField('hora', date("H:i:s"));

    procesarAsignatura($TBS, $conn, $clave);
    procesarPresentacion($TBS, $conn, $clave);
    procesarTemasYSubtemas($TBS, $conn, $clave);
    procesarBloquesTemasTexto($TBS, $conn, $clave);
}

// Uso:
// Solo necesitas cambiar la variable $CLAVE_ASIGNATURA al inicio del archivo
// para probar con diferentes asignaturas
// Asegúrate de que $TBS esté definido antes de llamar a esta función
if (isset($TBS)) {
    reemplazarPlaceholders($TBS, $conn, $CLAVE_ASIGNATURA);
} else {
    error_log("Error: La variable \$TBS no está definida");
}
?>