<?php

// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'my_site_art';
$username = 'root';  // Cambiar si tienes un usuario específico
$password = '';  // Cambiar si tienes una contraseña establecida

try {
    // Crear la conexión con PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configurar el modo de error para lanzar excepciones en caso de fallo
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // En caso de error, mostrar mensaje y detener ejecución
    die("Error de conexión: " . $e->getMessage());
}

?>