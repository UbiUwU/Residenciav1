<?php
// generar_ticket.php
include 'includes/db.php';

if (!function_exists('header')) {
    die('The header function is not available.');
}

if (!isset($_GET['plantilla_id'])) {
    header("Location: gestionar_plantillas.php");
    exit();
}

$plantilla_id = $_GET['plantilla_id'];
$stmt = $conn->prepare("SELECT * FROM plantillas WHERE id = :id");
$stmt->bindParam(':id', $plantilla_id);
$stmt->execute();
$plantilla = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$plantilla) {
    echo "<script>alert('Plantilla no encontrada.'); window.location.href = 'gestionar_plantillas.php';</script>";
    exit();
}

// Obtener productos
$stmt = $conn->query("SELECT * FROM productos");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generar el contenido del ticket
$contenido_ticket = file_get_contents($plantilla['archivo']);
$contenido_ticket = str_replace("{fecha}", date("d/m/Y"), $contenido_ticket);
$contenido_ticket = str_replace("{hora}", date("H:i:s"), $contenido_ticket);

$lista_productos = "";
foreach ($productos as $producto) {
    $lista_productos .= "<li>{$producto['nombre']} - {$producto['precio']} USD</li>";
}
$contenido_ticket = str_replace("{productos}", $lista_productos, $contenido_ticket);

// Guardar el ticket en un archivo
$nombre_ticket = "ticket_" . time() . ".html";
file_put_contents($nombre_ticket, $contenido_ticket);

// Forzar la descarga del ticket
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=$nombre_ticket");
readfile($nombre_ticket);
exit();
?>