<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../controllers/mensajeController.php';

verificarAutenticacion();

$usuario_id = $_SESSION['usuario_id'];

// Detectar si el usuario actual es admin (esto se podría mejorar)
$sql = "SELECT tipo_usuario FROM usuarios WHERE id_usuario = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$es_admin = ($usuario && $usuario['tipo_usuario'] === 'admin');

$mensajes = MensajeController::obtenerMensajes($usuario_id, $es_admin);
echo json_encode($mensajes);

?>
