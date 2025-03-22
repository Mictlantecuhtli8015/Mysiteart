<?php

session_start(); // Iniciar sesión para CSRF y seguridad

// Generar token CSRF si no existe
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Función para validar token CSRF en formularios
function validarCSRF($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        die("CSRF Attack Detected!");
    }
}

// Clave secreta para encriptación AES-256
$key = 'clave_secreta_segura';

// Función para encriptar datos sensibles
function encryptData($data, $key) {
    $cipher = "AES-256-CBC";
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
    $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

// Función para desencriptar datos sensibles
function decryptData($encryptedData, $key) {
    $cipher = "AES-256-CBC";
    $data = base64_decode($encryptedData);
    $ivLength = openssl_cipher_iv_length($cipher);
    $iv = substr($data, 0, $ivLength);
    $encrypted = substr($data, $ivLength);
    return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
}

// Protección contra inyección SQL y XSS
function limpiarEntrada($dato) {
    return htmlspecialchars(strip_tags(trim($dato)), ENT_QUOTES, 'UTF-8');
}

// Protección contra ataques de fuerza bruta en login
if (!isset($_SESSION['intentos_login'])) {
    $_SESSION['intentos_login'] = [];
}

function verificarIntentosLogin($correo) {
    if (!isset($_SESSION['intentos_login'][$correo])) {
        $_SESSION['intentos_login'][$correo] = 0;
    }
    $_SESSION['intentos_login'][$correo]++;
    if ($_SESSION['intentos_login'][$correo] > 5) {
        die("Demasiados intentos fallidos. Intenta más tarde.");
    }
}

?>