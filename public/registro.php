<?php
// Asegurarnos de que no hay salida antes del header
ob_start();

// Configurar manejo de errores
error_reporting(E_ALL);
ini_set('display_errors', 0); // Desactivar la salida de errores directa

// Función para manejar errores
function handleError($errno, $errstr, $errfile, $errline) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor',
        'debug' => [
            'error' => $errstr,
            'file' => $errfile,
            'line' => $errline
        ]
    ]);
    exit;
}

// Función para manejar excepciones
function handleException($e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor',
        'debug' => [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
    exit;
}

// Establecer manejadores de errores
set_error_handler('handleError');
set_exception_handler('handleException');

try {
    // Verificar que los archivos de configuración existen
    if (!file_exists(__DIR__ . '/../config/cdb.php')) {
        throw new Exception('Archivo cdb.php no encontrado');
    }
    if (!file_exists(__DIR__ . '/../config/seguridad.php')) {
        throw new Exception('Archivo seguridad.php no encontrado');
    }

    require_once __DIR__ . '/../config/cdb.php';
    require_once __DIR__ . '/../config/seguridad.php';

    // Verificar que la conexión a la base de datos está activa
    if (!isset($conn) || !($conn instanceof PDO)) {
        throw new Exception('Conexión a la base de datos no establecida');
    }

    // Obtener datos JSON del cuerpo de la solicitud
    $json = file_get_contents('php://input');
    if ($json === false) {
        throw new Exception('Error al leer el cuerpo de la solicitud');
    }

    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
    }

    $response = [
        'success' => false,
        'message' => '',
        'redirect' => '',
        'debug' => [
            'json_received' => $json,
            'data_decoded' => $data,
            'request_method' => $_SERVER['REQUEST_METHOD']
        ]
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!$data) {
            $response['message'] = "Error al decodificar JSON";
            $response['debug']['json_error'] = json_last_error_msg();
            echo json_encode($response);
            exit;
        }

        // Validar datos requeridos
        $campos_requeridos = ['nombre', 'correo', 'contraseña', 'tipo_usuario'];
        foreach ($campos_requeridos as $campo) {
            if (!isset($data[$campo]) || empty($data[$campo])) {
                $response['message'] = "El campo {$campo} es requerido";
                echo json_encode($response);
                exit;
            }
        }

        // Limpiar y validar datos
        $nombre = limpiarEntrada($data['nombre']);
        $correo = strtolower(limpiarEntrada($data['correo']));
        $contraseña = $data['contraseña'];
        $tipo_usuario = limpiarEntrada($data['tipo_usuario']);

        // Validar formato de correo
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = "El formato del correo electrónico no es válido";
            echo json_encode($response);
            exit;
        }

        // Validar tipo de usuario
        $tipos_validos = ['artista', 'comprador'];
        if (!in_array($tipo_usuario, $tipos_validos)) {
            $response['message'] = "Tipo de usuario no válido";
            echo json_encode($response);
            exit;
        }

        // Encriptar correo
        $correo_encriptado = encryptData($correo, $key, true);

        // Verificar si el correo ya está registrado
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo_encriptado]);
        if ($stmt->rowCount() > 0) {
            $response['message'] = "Este correo electrónico ya está registrado";
            echo json_encode($response);
            exit;
        }

        // Hash de la contraseña
        $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

        try {
            // Insertar nuevo usuario
            $stmt = $conn->prepare("
                INSERT INTO usuarios (nombre, correo, contraseña, tipo_usuario) 
                VALUES (?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $nombre,
                $correo_encriptado,
                $contraseña_hash,
                $tipo_usuario
            ]);

            if ($stmt->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = "Usuario registrado exitosamente";
                $response['redirect'] = '/Mysiteart/frontend/login.html';
            } else {
                $response['message'] = "Error al registrar el usuario";
            }
        } catch (PDOException $e) {
            $response['message'] = "Error al registrar el usuario: " . $e->getMessage();
            $response['debug']['error'] = $e->getMessage();
            $response['debug']['error_code'] = $e->getCode();
        }
    } else {
        $response['message'] = "Método no permitido";
        $response['debug']['error'] = "Método HTTP incorrecto: " . $_SERVER['REQUEST_METHOD'];
    }

    // Limpiar cualquier salida anterior
    ob_clean();
    
    // Establecer el header de JSON
    header('Content-Type: application/json');
    
    // Enviar la respuesta
    echo json_encode($response);
    
} catch (Exception $e) {
    // Limpiar cualquier salida anterior
    ob_clean();
    
    // Establecer el header de JSON
    header('Content-Type: application/json');
    
    // Enviar la respuesta de error
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor: ' . $e->getMessage(),
        'debug' => [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]
    ]);
}

// Enviar la salida
ob_end_flush(); 