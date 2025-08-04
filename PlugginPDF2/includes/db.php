<?php
// includes/db.php

// Cargar variables de entorno desde archivo .env si existe
if (file_exists(__DIR__ . '/../../Back/.env')) {
    $lines = file(__DIR__ . '/../../Back/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Ignorar comentarios
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Configuración de base de datos usando variables de entorno con valores por defecto
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$port = $_ENV['DB_PORT'];

try {
    // Cadena de conexión para PostgreSQL
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configuración para mejorar rendimiento en consultas
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Forzar el uso de UTF-8 para la conexión
    $conn->exec("SET NAMES 'UTF8'");

    // Mensaje de conexión exitosa (solo para desarrollo)
    // echo "Conexión a la base de datos establecida correctamente";

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    die();
}

// Función para consultas rápidas
function query($sql, $params = []) {
    global $conn;
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        die("Error en la consulta: " . $e->getMessage());
    }
}

// Función para obtener un solo registro
function getOne($sql, $params = []) {
    $stmt = query($sql, $params);
    return $stmt ? $stmt->fetch() : false;
}

// Función para obtener todos los registros
function getAll($sql, $params = []) {
    $stmt = query($sql, $params);
    return $stmt ? $stmt->fetchAll() : false;
}

// Función para insertar datos y retornar el ID
function insert($sql, $params = []) {
    global $conn;
    $stmt = query($sql, $params);
    return $stmt ? $conn->lastInsertId() : false;
}
?>
