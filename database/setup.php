<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/cdb.php';
require_once __DIR__ . '/../config/seguridad.php';

try {
    // Verificar si la base de datos existe
    $conn->query("USE my_site_art");
    echo "Base de datos encontrada.\n";

    // Verificar si la tabla usuarios existe
    $stmt = $conn->query("SHOW TABLES LIKE 'usuarios'");
    if ($stmt->rowCount() == 0) {
        // Crear tabla usuarios si no existe
        $sql = "CREATE TABLE IF NOT EXISTS usuarios (
            id_usuario INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            correo VARCHAR(100) UNIQUE NOT NULL,
            contraseña VARCHAR(255) NOT NULL,
            tipo_usuario ENUM('artista', 'comprador', 'admin') NOT NULL,
            fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->exec($sql);
        echo "Tabla usuarios creada.\n";
    } else {
        echo "Tabla usuarios encontrada.\n";
    }

    // Verificar si existe el usuario administrador
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE tipo_usuario = 'admin' LIMIT 1");
    $stmt->execute();
    
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
        echo "Usuario administrador creado.\n";
    } else {
        echo "Usuario administrador encontrado.\n";
    }

    echo "Configuración completada exitosamente.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 