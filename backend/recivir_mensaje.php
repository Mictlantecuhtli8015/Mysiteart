<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../controllers/mensajeController.php';

verificarAutenticacion();

$usuario_id = $_SESSION['usuario_id'];

// Consultar tipo de usuario
$sql = "SELECT tipo_usuario FROM usuarios WHERE id_usuario = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$es_admin = ($usuario && $usuario['tipo_usuario'] === 'admin');

// Obtener mensajes tipo chat
$mensajes = MensajeController::obtenerMensajes($usuario_id, $es_admin);

// Enviar como respuesta JSON
echo json_encode($mensajes);

?>
