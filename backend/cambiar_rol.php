<?php
require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../config/session.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["error" => "Debe iniciar sesión para realizar esta acción"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_rol = limpiarEntrada($_POST['nuevo_rol']);
    $usuario_id = $_SESSION['usuario_id'];
    
    // Validar que el nuevo rol sea válido
    if (!in_array($nuevo_rol, ['artista', 'comprador'])) {
        echo json_encode(["error" => "Rol no válido"]);
        exit;
    }
    
    try {
        // Verificar si el usuario actual es administrador
        $sql_check = "SELECT tipo_usuario FROM usuarios WHERE id_usuario = :id";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->execute(['id' => $usuario_id]);
        $usuario_actual = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        // Solo permitir cambio entre artista y comprador
        if ($usuario_actual['tipo_usuario'] === 'admin') {
            echo json_encode(["error" => "Los administradores no pueden cambiar su rol"]);
            exit;
        }
        
        // Actualizar el rol del usuario
        $sql = "UPDATE usuarios SET tipo_usuario = :nuevo_rol WHERE id_usuario = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'nuevo_rol' => $nuevo_rol,
            'id' => $usuario_id
        ]);
        
        // Actualizar la sesión
        $_SESSION['tipo_usuario'] = $nuevo_rol;
        
        echo json_encode([
            "message" => "Rol actualizado exitosamente",
            "nuevo_rol" => $nuevo_rol
        ]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error al cambiar el rol: " . $e->getMessage()]);
    }
}
?> 