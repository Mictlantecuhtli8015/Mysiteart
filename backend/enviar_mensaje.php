<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../controllers/mensajeController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarAutenticacion();

    $emisor = $_SESSION['usuario_id'];
    $receptor = limpiarEntrada($_POST['receptor']); // Puede ser 0 si va a todos los admins
    $asunto = limpiarEntrada($_POST['asunto']);
    $contenido = limpiarEntrada($_POST['contenido']);

    $enviado = MensajeController::enviarMensaje($emisor, $receptor, $asunto, $contenido);

    if ($enviado) {
        echo json_encode(["message" => "Mensaje enviado correctamente"]);
    } else {
        echo json_encode(["error" => "Error al enviar el mensaje"]);
    }
}

?>
