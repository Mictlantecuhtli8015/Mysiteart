<?php
require_once __DIR__ . '/../config/cdb.php';
require_once __DIR__ . '/../config/seguridad.php';

try {
    // Verificar si el usuario administrador ya existe
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
    $stmt->execute([encryptData('mictlantecuhtli8015@proton.me', $key, true)]);
    
    if ($stmt->rowCount() == 0) {
        // Crear usuario administrador
        $nombre = 'Mictlan';
        $correo = 'mictlantecuhtli8015@proton.me';
        $contraseña = password_hash('0Landa_5528', PASSWORD_BCRYPT);
        $tipo_usuario = 'admin';

        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contraseña, tipo_usuario) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $nombre,
            encryptData($correo, $key, true),
            $contraseña,
            $tipo_usuario
        ]);
        
        echo "Usuario administrador creado exitosamente.\n";
    } else {
        echo "El usuario administrador ya existe.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 