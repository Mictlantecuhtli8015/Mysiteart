<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/security.php';

class MensajeController {

    // Enviar un mensaje (incluye soporte para mensajes a administradores en grupo)
    public static function enviarMensaje($emisor, $receptor, $asunto, $contenido) {
        global $conn;
        $sql = "INSERT INTO mensajes (id_emisor, id_receptor, asunto, contenido) VALUES (:emisor, :receptor, :asunto, :contenido)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            'emisor' => $emisor,
            'receptor' => $receptor, // Si es 0, lo ven todos los admins
            'asunto' => $asunto,
            'contenido' => $contenido
        ]);
    }

    // Obtener mensajes según el tipo de usuario
    public static function obtenerMensajes($usuario_id, $es_admin = false) {
        global $conn;

        if ($es_admin) {
            // Admin ve mensajes dirigidos a id_receptor = 0 (mensajes grupales)
            $sql = "SELECT * FROM mensajes WHERE id_receptor = 0 OR id_receptor = :id ORDER BY fecha_envio DESC";
        } else {
            // Usuario ve mensajes que le enviaron directamente
            $sql = "SELECT * FROM mensajes WHERE id_receptor = :id ORDER BY fecha_envio DESC";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function marcarComoLeido($id_mensaje) {
        global $conn;
        $sql = "UPDATE mensajes SET leido = TRUE WHERE id_mensaje = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute(['id' => $id_mensaje]);
    }


}

?>