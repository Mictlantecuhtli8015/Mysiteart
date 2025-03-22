<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../config/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = limpiarEntrada($_POST['nombre']);
    $correo = limpiarEntrada($_POST['correo']);
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);
    $tipo_usuario = limpiarEntrada($_POST['tipo_usuario']);

    try {
        $sql = "INSERT INTO usuarios (nombre, correo, contraseña, tipo_usuario) VALUES (:nombre, :correo, :contraseña, :tipo_usuario)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'correo' => encryptData($correo, $key),
            'contraseña' => $contraseña,
            'tipo_usuario' => $tipo_usuario
        ]);
        echo json_encode(["message" => "Registro exitoso"]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error en el registro: " . $e->getMessage()]);
    }
}

?>