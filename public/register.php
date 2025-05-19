<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/cdb.php';
require_once __DIR__ . '/../config/seguridad.php';

// Obtener datos JSON del cuerpo de la solicitud
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$response = [
    'success' => false,
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = limpiarEntrada($data['nombre']);
    $correo = limpiarEntrada($data['correo']);
    $contraseña = $data['contraseña'];
    $tipo_usuario = limpiarEntrada($data['tipo_usuario']);

    // Validaciones básicas
    if (empty($nombre) || empty($correo) || empty($contraseña)) {
        $response['message'] = "Todos los campos son obligatorios";
    } else {
        try {
            // Verificar si el correo ya existe
            $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
            $stmt->execute([encryptData($correo, $key, true)]);
            
            if ($stmt->rowCount() > 0) {
                $response['message'] = "Este correo electrónico ya está registrado";
            } else {
                // Insertar nuevo usuario
                $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contraseña, tipo_usuario) VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $nombre,
                    encryptData($correo, $key, true),
                    password_hash($contraseña, PASSWORD_BCRYPT),
                    $tipo_usuario
                ]);
                
                $response['success'] = true;
                $response['message'] = "Registro exitoso. Ahora puedes iniciar sesión.";
            }
        } catch (PDOException $e) {
            $response['message'] = "Error en el registro: " . $e->getMessage();
        }
    }
} else {
    $response['message'] = "Método no permitido";
}

echo json_encode($response);
