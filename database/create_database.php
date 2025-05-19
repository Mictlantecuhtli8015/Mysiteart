<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Conectar sin seleccionar base de datos
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Crear la base de datos si no existe
    $sql = "CREATE DATABASE IF NOT EXISTS my_site_art";
    $conn->exec($sql);
    echo "Base de datos creada exitosamente\n";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 