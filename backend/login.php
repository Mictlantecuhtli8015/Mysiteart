<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../config/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = limpiarEntrada($_POST['correo']);
    $contraseña = $_POST['contraseña'];
    
    verificarIntentosLogin($correo);
    
    try {
        $sql = "SELECT id_usuario, correo, contraseña FROM usuarios WHERE correo = :correo";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['correo' => encryptData($correo, $key)]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            echo json_encode(["message" => "Inicio de sesión exitoso"]);
        } else {
            echo json_encode(["error" => "Credenciales incorrectas"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error en el inicio de sesión: " . $e->getMessage()]);
    }
}

?>