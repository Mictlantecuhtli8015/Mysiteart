<?php
session_start();
header('Content-Type: application/json');

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

echo json_encode([
    'success' => true,
    'message' => 'Sesión cerrada exitosamente'
]); 