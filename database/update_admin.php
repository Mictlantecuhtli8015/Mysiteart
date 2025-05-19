<?php
require_once __DIR__ . '/../config/cdb.php';
require_once __DIR__ . '/../config/seguridad.php';

try {
    // Obtener el usuario admin
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre = 'Mictlan'");
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        echo "<h2>Información del Usuario Admin</h2>";
        echo "<p>Nombre: " . htmlspecialchars($usuario['nombre']) . "</p>";
        echo "<p>Correo: mictlantecuhtli8015@proton.me</p>";
        echo "<p>Contraseña actual (hash): " . htmlspecialchars($usuario['contraseña']) . "</p>";
        
        // Crear nueva contraseña hasheada
        $nueva_contraseña = '0Landa_5528';
        $contraseña_hash = password_hash($nueva_contraseña, PASSWORD_DEFAULT);
        
        // Actualizar contraseña
        $stmt = $conn->prepare("UPDATE usuarios SET contraseña = ? WHERE nombre = 'Mictlan'");
        $stmt->execute([$contraseña_hash]);
        
        echo "<p style='color: green;'>Contraseña actualizada correctamente</p>";
        echo "<p>Nueva contraseña: " . htmlspecialchars($nueva_contraseña) . "</p>";
        echo "<p>Nuevo hash: " . htmlspecialchars($contraseña_hash) . "</p>";
        
        // Verificar que la contraseña funciona
        if (password_verify($nueva_contraseña, $contraseña_hash)) {
            echo "<p style='color: green;'>Verificación de contraseña exitosa</p>";
        } else {
            echo "<p style='color: red;'>Error en la verificación de contraseña</p>";
        }
    } else {
        echo "<p style='color: red;'>Usuario no encontrado</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
} 