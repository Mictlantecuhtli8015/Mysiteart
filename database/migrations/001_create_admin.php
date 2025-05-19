<?php
require_once __DIR__ . '/../../config/cdb.php';
require_once __DIR__ . '/../../config/seguridad.php';

try {
    // Verificar si ya existe un administrador
    $sql_check = "SELECT id_usuario FROM usuarios WHERE tipo_usuario = 'admin' LIMIT 1";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute();
    
    if ($stmt_check->rowCount() > 0) {
        echo "Ya existe un administrador en el sistema.\n";
        exit;
    }

    // Datos del administrador principal
    $nombre = 'Mictlan';
    $correo = 'mictlantecuhtli8015@proton.me';
    $contraseña = password_hash('0Landa_5528', PASSWORD_BCRYPT);
    $tipo_usuario = 'admin';

    // Insertar el administrador
    $sql = "INSERT INTO usuarios (nombre, correo, contraseña, tipo_usuario) VALUES (:nombre, :correo, :contraseña, :tipo_usuario)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'nombre' => $nombre,
        'correo' => encryptData($correo, $key),
        'contraseña' => $contraseña,
        'tipo_usuario' => $tipo_usuario
    ]);

    echo "Administrador principal creado exitosamente.\n";
} catch (PDOException $e) {
    echo "Error al crear el administrador: " . $e->getMessage() . "\n";
}
?> 