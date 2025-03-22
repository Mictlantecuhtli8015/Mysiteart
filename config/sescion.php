<?php

session_start(); // Iniciar sesión
require_once __DIR__ . '/security.php'; // Incluir medidas de seguridad

// Verificar si el usuario está autenticado
function verificarAutenticacion() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: /public/login.php");
        exit();
    }
}

// Cerrar sesión del usuario
function cerrarSesion() {
    session_unset();
    session_destroy();
    header("Location: /public/login.php");
    exit();
}

?>
