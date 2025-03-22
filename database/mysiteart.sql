CREATE DATABASE IF NOT EXISTS my_site_art;
USE my_site_art;

-- Tabla de Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('artista', 'comprador', 'admin') NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Obras de Arte
CREATE TABLE IF NOT EXISTS obras (
    id_obra INT AUTO_INCREMENT PRIMARY KEY,
    id_artista INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    estado ENUM('disponible', 'vendido') DEFAULT 'disponible',
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_artista) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- Tabla de Pedidos Personalizados
CREATE TABLE IF NOT EXISTS pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_comprador INT NOT NULL,
    descripcion TEXT NOT NULL,
    precio_ofertado DECIMAL(10,2),
    estado ENUM('pendiente', 'aceptado', 'rechazado') DEFAULT 'pendiente',
    id_artista_responsable INT,
    FOREIGN KEY (id_comprador) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_artista_responsable) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
);

-- Tabla de Transacciones
CREATE TABLE IF NOT EXISTS transacciones (
    id_transaccion INT AUTO_INCREMENT PRIMARY KEY,
    id_comprador INT NOT NULL,
    id_obra INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    metodo_pago ENUM('tarjeta', 'paypal', 'transferencia') NOT NULL,
    estado ENUM('pendiente', 'completado') DEFAULT 'pendiente',
    fecha_transaccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_comprador) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_obra) REFERENCES obras(id_obra) ON DELETE CASCADE
);