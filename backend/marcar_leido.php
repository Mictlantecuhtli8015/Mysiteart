<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../controllers/mensajeController.php';

verificarAutenticacion();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_mensaje'])) {
    $id_mensaje = intval($_POST['id_mensaje']);
    $resultado = MensajeController::marcarComoLeido($id_mensaje);

    if ($resultado) {
        echo json_encode(["success" => true, "message" => "Mensaje marcado como leído."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar el estado."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "ID de mensaje no recibido."]);
}

?>
