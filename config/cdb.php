<?php
// Asegurarnos de que no hay salida antes de los headers
ob_start();

// Configurar manejo de errores
error_reporting(E_ALL);
ini_set('display_errors', 0); // Desactivar la salida de errores directa

try {
    // Configuración de la base de datos
    $host = 'localhost';
    $dbname = 'my_site_art';
    $username = 'root';
    $password = '';
    
    // Crear conexión PDO
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    
    // Verificar la conexión
    $conn->query("SELECT 1");
    
} catch (PDOException $e) {
    error_log('Error de conexión a la base de datos: ' . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos',
        'debug' => [
            'error' => $e->getMessage(),
            'code' => $e->getCode()
        ]
    ]);
    exit;
}

// Enviar cualquier salida pendiente
ob_end_flush();

?>