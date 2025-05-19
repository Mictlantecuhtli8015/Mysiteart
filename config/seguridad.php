<?php
// Asegurarnos de que no hay salida antes de los headers
ob_start();

// Configurar manejo de errores
error_reporting(E_ALL);
ini_set('display_errors', 0); // Desactivar la salida de errores directa

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

// Configuración de seguridad
$key = 'MySiteArt2024SecureKey12345678901234567890123456789012'; // Clave de 32 bytes para AES-256
$iv = 'MySiteArt2024IV'; // IV de 16 bytes para AES-256-CBC

// Función para encriptar datos
function encryptData($data, $key, $isEmail = false) {
    global $iv;
    
    try {
        // Asegurarnos de que el IV tenga exactamente 16 bytes
        $iv = substr(str_pad($iv, 16, "\0"), 0, 16);
        
        // Si es un email, asegurarnos de que esté en minúsculas
        if ($isEmail) {
            $data = strtolower($data);
        }
        
        // Encriptar usando AES-256-CBC
        $encrypted = openssl_encrypt(
            $data,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        
        if ($encrypted === false) {
            throw new Exception('Error en la encriptación: ' . openssl_error_string());
        }
        
        // Convertir a base64 para almacenamiento seguro
        return base64_encode($encrypted);
    } catch (Exception $e) {
        error_log('Error en encryptData: ' . $e->getMessage());
        throw $e;
    }
}

// Función para desencriptar datos
function decryptData($encryptedData, $key) {
    global $iv;
    
    try {
        // Asegurarnos de que el IV tenga exactamente 16 bytes
        $iv = substr(str_pad($iv, 16, "\0"), 0, 16);
        
        // Decodificar base64
        $encryptedData = base64_decode($encryptedData);
        if ($encryptedData === false) {
            throw new Exception('Error al decodificar base64');
        }
        
        // Desencriptar
        $decrypted = openssl_decrypt(
            $encryptedData,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        
        if ($decrypted === false) {
            throw new Exception('Error en la desencriptación: ' . openssl_error_string());
        }
        
        return $decrypted;
    } catch (Exception $e) {
        error_log('Error en decryptData: ' . $e->getMessage());
        throw $e;
    }
}

// Función para limpiar entradas
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

// --- Filtro que reemplaza lenguaje ofensivo con #####
function filtrarPalabrasProhibidas($texto) {
    $prohibidas = ['idiota', 'estúpido', 'mierda', 'puta', 'imbécil', 'maldito'];
    foreach ($prohibidas as $palabra) {
        $regex = '/\b' . preg_quote($palabra, '/') . '\b/i';
        $texto = preg_replace($regex, '#####', $texto);
    }
    return $texto;
}

// Enviar cualquier salida pendiente
ob_end_flush();

?>