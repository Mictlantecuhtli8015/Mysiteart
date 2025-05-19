<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../controllers/mensajeController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarAutenticacion();

    $emisor = $_SESSION['usuario_id'];
    $receptor = limpiarEntrada($_POST['receptor']);
    $contenido = limpiarEntrada($_POST['contenido']);
    $respuesta_a = isset($_POST['respuesta_a']) ? intval($_POST['respuesta_a']) : null;

    // Validar longitud del mensaje
    if (strlen($contenido) > 1000) {
        echo json_encode(["error" => "El mensaje no puede exceder los 1000 caracteres."]);
        exit;
    }

    // Establecer el estado del mensaje como 'Enviado'
    $estado = 'Enviado'; // En el futuro se podrá actualizar a 'Leído'

    // Enviar mensaje
    $enviado = MensajeController::enviarMensaje($emisor, $receptor, $contenido, $respuesta_a, $estado);

    if ($enviado) {
        echo json_encode(["message" => "Mensaje enviado correctamente."]);
    } else {
        echo json_encode(["error" => "Error al enviar el mensaje."]);
    }
}
?>
