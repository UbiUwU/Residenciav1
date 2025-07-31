<?php
require_once 'includes/db.php';

// Configuración de directorios
define('DOCS_DIR', 'docx/');
if (!file_exists(DOCS_DIR)) {
    mkdir(DOCS_DIR, 0755, true);
}

// Subir plantilla
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['plantilla'])) {
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

    if ($extension !== 'docx' || $mime_type !== 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
        $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Solo se permiten archivos Word (.docx) válidos.'];
    } elseif (filesize($ruta_temporal) > 5 * 1024 * 1024) { // 5MB
        $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'El archivo no debe exceder los 5MB.'];
    } else {
        if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
            try {
                $tipo = $_POST['tipo'] ?? '';
                if (!in_array($tipo, ['avance', 'instrumentacion', 'reporte_final'])) {
                    $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Tipo de plantilla inválido.'];
                    header('Location: index.php');
                    exit();
                }

                $sql = "INSERT INTO plantillas (nombre, descripcion, archivo, tipo)
                        VALUES (:nombre, :descripcion, :archivo, :tipo)
                        RETURNING id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':descripcion', $descripcion);
                $stmt->bindParam(':archivo', $nombre_archivo);
                $stmt->bindParam(':tipo', $tipo);
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

                $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Plantilla subida con éxito. ID: ' . $resultado['id']];
            } catch (PDOException $e) {
                unlink($ruta_destino); // Eliminar archivo si falla DB
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error al registrar la plantilla: ' . $e->getMessage()];
            }
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error al subir el archivo.'];
        }
    }

    header('Location: index.php');
    exit();
}

// Obtener plantillas
$plantillas = getAll("SELECT id, nombre, descripcion, archivo, tipo, estado, TO_CHAR(creado_en, 'DD/MM/YYYY HH24:MI') as fecha_creacion FROM plantillas ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestionar Plantillas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .file-icon {
            font-size: 2rem;
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

        <div class="card shadow mb-5">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">Subir Nueva Plantilla</h2>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="plantilla" class="form-label">Archivo .docx *</label>
                            <input type="file" class="form-control" id="plantilla" name="plantilla" accept=".docx"
                                required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tipo" class="form-label">Tipo *</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Selecciona una opción</option>
                                <option value="avance">Avance</option>
                                <option value="instrumentacion">Instrumentación</option>
                                <option value="reporte_final">Reporte Final</option>
                            </select>
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

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">Plantillas Registradas</h2>
            </div>
            <div class="card-body">
                <?php if (!empty($plantillas)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
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
                                        <td><?= htmlspecialchars($plantilla['tipo']) ?></td>
                                        <td><?= htmlspecialchars($plantilla['estado']) ?></td>
                                        <td>
                                            <i class="bi bi-file-earmark-word file-icon"></i>
                                            <?= htmlspecialchars($plantilla['archivo']) ?>
                                        </td>
                                        <td><?= $plantilla['fecha_creacion'] ?></td>
                                        <td>
                                            <a href="<?= DOCS_DIR . $plantilla['archivo'] ?>"
                                                class="btn btn-success btn-sm action-btn" download>
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <a href="download.php?id=<?= $plantilla['id'] ?>&tipo=<?= urlencode($plantilla['tipo']) ?>"
                                                class="btn btn-primary btn-sm action-btn" title="Generar Ticket">
                                                <i class="bi bi-ticket-perforated"></i>
                                            </a>

                                            <button class="btn btn-danger btn-sm action-btn"
                                                onclick="confirm('¿Eliminar plantilla?')">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>