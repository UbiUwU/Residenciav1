<?php
// gestionar_plantillas.php
include 'includes/db.php';

// Subir plantilla
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['plantilla'])) {
    $nombre = $_POST['nombre'];
    $archivo = $_FILES['plantilla']['name'];
    $ruta_temporal = $_FILES['plantilla']['tmp_name'];
    $ruta_destino = "doc/" . basename($archivo);

    // Validar que el archivo sea un Word (.docx)
    $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
    if ($extension != 'docx') {
        echo "<script>alert('Solo se permiten archivos Word (.docx).');</script>";
    } else {
        // Mover el archivo al servidor
        if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
            $stmt = $conn->prepare("INSERT INTO plantillas (nombre, archivo) VALUES (:nombre, :archivo)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':archivo', $ruta_destino);
            $stmt->execute();
            echo "<script>alert('Plantilla subida con éxito.');</script>";
        } else {
            echo "<script>alert('Error al subir la plantilla.');</script>";
        }
    }
}

// Obtener plantillas registradas
$stmt = $conn->query("SELECT * FROM plantillas");
$plantillas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Plantillas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Gestionar Plantillas</h1>
        
        <!-- Formulario para subir plantillas -->
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de la Plantilla</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="plantilla" class="form-label">Subir Plantilla (Word .docx)</label>
                <input type="file" class="form-control" id="plantilla" name="plantilla" accept=".docx" required>
            </div>
            <button type="submit" class="btn btn-primary">Subir Plantilla</button>
        </form>

        <!-- Lista de plantillas registradas -->
        <h2 class="mt-5">Plantillas Registradas</h2>
        <?php if (count($plantillas) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Archivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plantillas as $plantilla): ?>
                        <tr>
                            <td><?php echo $plantilla['id']; ?></td>
                            <td><?php echo $plantilla['nombre']; ?></td>
                            <td><?php echo $plantilla['archivo']; ?></td>
                            <td>
                                <form method="POST" action="download.php" style="display: inline;">
                                    <input type="hidden" name="plantilla_id" value="<?php echo $plantilla['id']; ?>">
                                    <button type="submit" class="btn btn-success">Generar Ticket</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">No hay plantillas registradas.</div>
        <?php endif; ?>
    </div>
</body>
</html>