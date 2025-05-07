<?php
// gestionar_plantillas.php
require_once 'includes/db.php';


// Configuración de directorios
define('DOCS_DIR', 'doc/');
if (!file_exists(DOCS_DIR)) {
    mkdir(DOCS_DIR, 0755, true);
}

// Subir plantilla
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['plantilla'])) {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $archivo = $_FILES['plantilla']['name'];
    $ruta_temporal = $_FILES['plantilla']['tmp_name'];

    // Generar nombre único para el archivo
    $nombre_archivo = uniqid() . '_' . preg_replace('/[^A-Za-z0-9\.\-]/', '_', $archivo);
    $ruta_destino = DOCS_DIR . $nombre_archivo;

    // Validar el archivo
    $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
    $mime_type = mime_content_type($ruta_temporal);

    if ($extension != 'docx' || !in_array($mime_type, ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
        $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Solo se permiten archivos Word (.docx) válidos.'];
    } elseif (filesize($ruta_temporal) > 5 * 1024 * 1024) { // 5MB máximo
        $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'El archivo no debe exceder los 5MB.'];
    } else {
        // Mover el archivo al servidor
        if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
            try {
                $sql = "INSERT INTO plantillas (nombre, descripcion, archivo) 
                        VALUES (:nombre, :descripcion, :archivo)
                        RETURNING id";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':descripcion', $descripcion);
                $stmt->bindParam(':archivo', $nombre_archivo);
                $stmt->execute();

                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['mensaje'] = ['tipo' => 'exito', 'texto' => 'Plantilla subida con éxito. ID: ' . $resultado['id']];
            } catch (PDOException $e) {
                // Eliminar archivo si falla la inserción en BD
                unlink($ruta_destino);
                $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Error al registrar la plantilla en la base de datos.'];
            }
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Error al subir el archivo.'];
        }
    }
    header('Location: gestionar_plantillas.php');
    exit();
}

// Obtener plantillas registradas
$plantillas = getAll("SELECT id, nombre, descripcion, archivo, 
                      TO_CHAR(created_at, 'DD/MM/YYYY HH24:MI') as fecha_creacion 
                      FROM plantillas ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Plantillas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .file-icon {
            font-size: 3rem;
            color: #0d6efd;
        }

        .action-btn {
            margin: 0 2px;
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5">
        <h1 class="text-center mb-4">Gestionar Plantillas</h1>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?> alert-dismissible fade show">
                <?= $_SESSION['mensaje']['texto'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <!-- Formulario para subir plantillas -->
        <div class="card shadow mb-5">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">Subir Nueva Plantilla</h2>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre de la Plantilla *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="plantilla" class="form-label">Archivo Word (.docx) *</label>
                            <input type="file" class="form-control" id="plantilla" name="plantilla" accept=".docx"
                                required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload"></i> Subir Plantilla
                    </button>
                </form>
            </div>
        </div>

        <!-- Lista de plantillas registradas -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">Plantillas Registradas</h2>
            </div>
            <div class="card-body">
                <?php if (!empty($plantillas)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Archivo</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($plantillas as $plantilla): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($plantilla['id']) ?></td>
                                        <td><?= htmlspecialchars($plantilla['nombre']) ?></td>
                                        <td><?= htmlspecialchars($plantilla['descripcion']) ?></td>
                                        <td>
                                            <i class="bi bi-file-earmark-word file-icon"></i>
                                            <?= htmlspecialchars($plantilla['archivo']) ?>
                                        </td>
                                        <td><?= $plantilla['fecha_creacion'] ?></td>
                                        <td>
                                            <a href="<?= DOCS_DIR . $plantilla['archivo'] ?>"
                                                class="btn btn-sm btn-success action-btn"
                                                download="<?= $plantilla['nombre'] . '.docx' ?>" title="Descargar">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <a href="download.php?id=<?= htmlspecialchars($plantilla['id']) ?>"
                                                class="btn btn-sm btn-primary action-btn" title="Generar Ticket"
                                                onclick="return confirm('¿Generar ticket con esta plantilla?')">
                                                <i class="bi bi-ticket-perforated"></i> Generar Ticket
                                            </a>
                                            <button class="btn btn-sm btn-danger action-btn"
                                                onclick="confirmarEliminacion(<?= $plantilla['id'] ?>, '<?= addslashes($plantilla['nombre']) ?>')"
                                                title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">No hay plantillas registradas.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar la plantilla "<span id="plantillaNombre"></span>"?</p>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="#" id="eliminarBtn" class="btn btn-danger">Eliminar</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarEliminacion(id, nombre) {
            document.getElementById('plantillaNombre').textContent = nombre;
            document.getElementById('eliminarBtn').href = 'eliminar_plantilla.php?id=' + id;
            new bootstrap.Modal(document.getElementById('confirmModal')).show();
        }
    </script>
</body>

</html>