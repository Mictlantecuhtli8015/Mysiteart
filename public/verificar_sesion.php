<?php
session_start();
header('Content-Type: application/json');

// Verificar si hay una sesión activa
if (isset($_SESSION['id_usuario'])) {
    echo json_encode([
        'success' => true,
        'nombre' => $_SESSION['nombre'],
        'tipo_usuario' => $_SESSION['tipo_usuario']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No hay sesión activa'
    ]);
} 