<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/security.php';

class MensajeController {

    // Enviar un mensaje tipo chat
    public static function enviarMensaje($emisor, $receptor, $contenido, $respuesta_a = null, $estado = 'Enviado') {
        global $conn;
        $sql = "INSERT INTO mensajes (id_emisor, id_receptor, contenido, id_mensaje_respuesta, estado)
                VALUES (:emisor, :receptor, :contenido, :respuesta_a, :estado)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            'emisor' => $emisor,
            'receptor' => $receptor,
            'contenido' => filtrarPalabrasProhibidas($contenido),
            'respuesta_a' => $respuesta_a,
            'estado' => $estado
        ]);
    }


    // Obtener mensajes tipo chat
    public static function obtenerMensajes($usuario_id, $es_admin = false) {
        global $conn;

        if ($es_admin) {
            $sql = "SELECT * FROM mensajes 
                    WHERE id_receptor = 0 OR id_receptor = :id OR id_emisor = :id
                    ORDER BY fecha_envio ASC";
        } else {
            $sql = "SELECT * FROM mensajes 
                    WHERE id_receptor = :id OR id_emisor = :id
                    ORDER BY fecha_envio ASC";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Marca como leido
    public static function marcarComoLeido($id_mensaje) {
    global $conn;
    $sql = "UPDATE mensajes SET leido = TRUE, estado = 'Leído' WHERE id_mensaje = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute(['id' => $id_mensaje]);
}

}
?>
