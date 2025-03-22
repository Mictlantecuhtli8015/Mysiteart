<?php

require_once __DIR__ . '/../config/security.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = limpiarEntrada($_POST['nombre']);
    $correo = limpiarEntrada($_POST['correo']);
    $mensaje = limpiarEntrada($_POST['mensaje']);
    
    $destinatario = "soporte@mysiteart.com";
    $asunto = "Consulta de soporte";
    $contenido = "Nombre: $nombre\nCorreo: $correo\nMensaje: $mensaje";
    
    if (mail($destinatario, $asunto, $contenido)) {
        echo json_encode(["message" => "Mensaje enviado con éxito"]);
    } else {
        echo json_encode(["error" => "Error al enviar el mensaje"]);
    }
}

?>
