<?php
include_once('includes/db.php'); // Conexión a la base de datos

/**
 * Obtiene todos los productos desde la base de datos.
 *
 * @param PDO $conn Conexión a la base de datos.
 * @return array Lista de productos.
 */
function obtenerProductos($conn) {
    try {
        $stmt = $conn->query("SELECT * FROM productos");
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Depuración: Mostrar productos en la consola
        error_log("Productos obtenidos: " . print_r($productos, true));

        return $productos;
    } catch (PDOException $e) {
        error_log("Error al obtener productos: " . $e->getMessage());
        return [];
    }
}

/**
 * Formatea la lista de productos para ser insertada en la plantilla.
 *
 * @param array $productos Lista de productos obtenidos de la base de datos.
 * @return string Cadena de productos en formato texto.
 */
function formatearListaProductos($productos) {
    $lista_productos = "";
    foreach ($productos as $producto) {
        $lista_productos .= "- {$producto['nombre']} - {$producto['precio']} USD\n - {$producto['descripcion']} -"  ;
    }
    return $lista_productos;
}


/**
 * Reemplaza los placeholders en la plantilla con los datos obtenidos.
 *
 * @param object $TBS Instancia de TinyButStrong.
 * @param PDO $conn Conexión a la base de datos.
 */
function reemplazarPlaceholders($TBS, $conn) {
    $productos = obtenerProductos($conn);
    
    $TBS->MergeField('fecha', date("d/m/Y"));
    $TBS->MergeField('hora', date("H:i:s"));
    
    // Crear sección para cada producto
    $secciones = [];
    foreach ($productos as $index => $producto) {
        $secciones[] = [
            'num' => $index + 1,
            'nombre' => $producto['nombre'],
            'precio' => number_format($producto['precio'], 2),
            'descripcion' => $producto['descripcion']
        ];
    }
    
    $TBS->MergeBlock('producto', $secciones);
}
?>
