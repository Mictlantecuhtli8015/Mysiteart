-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS my_site_art;
USE my_site_art;

-- Crear la tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(255) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('admin', 'artista', 'comprador') NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar usuario administrador por defecto
-- Contraseña: admin123 (hasheada con password_hash)
INSERT INTO usuarios (nombre, correo, contraseña, tipo_usuario)
VALUES (
    'Administrador',
    'admin@example.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin'
) ON DUPLICATE KEY UPDATE id_usuario = id_usuario; 