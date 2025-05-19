<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../config/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = limpiarEntrada($_POST['nombre']);
    $correo = limpiarEntrada($_POST['correo']);
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);
    $tipo_usuario = limpiarEntrada($_POST['tipo_usuario']);

    // Validar que el tipo de usuario sea válido
    if (!in_array($tipo_usuario, ['artista', 'comprador', 'admin'])) {
        echo json_encode(["error" => "Tipo de usuario no válido"]);
        exit;
    }

    // Verificar si se está intentando crear un administrador
    if ($tipo_usuario === 'admin') {
        // Verificar si el usuario actual es administrador
        if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
            echo json_encode(["error" => "Solo los administradores pueden crear cuentas de administrador"]);
            exit;
        }
    }

    try {
        // Verificar si el correo ya existe
        $sql_check = "SELECT id_usuario FROM usuarios WHERE correo = :correo";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->execute(['correo' => encryptData($correo, $key)]);
        
        if ($stmt_check->rowCount() > 0) {
            echo json_encode(["error" => "Este correo electrónico ya está registrado"]);
            exit;
        }

        $sql = "INSERT INTO usuarios (nombre, correo, contraseña, tipo_usuario) VALUES (:nombre, :correo, :contraseña, :tipo_usuario)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'correo' => encryptData($correo, $key),
            'contraseña' => $contraseña,
            'tipo_usuario' => $tipo_usuario
        ]);
        
        echo json_encode([
            "message" => "Registro exitoso",
            "tipo_usuario" => $tipo_usuario
        ]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error en el registro: " . $e->getMessage()]);
    }
}

?>