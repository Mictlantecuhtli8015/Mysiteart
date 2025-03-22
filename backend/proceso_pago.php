<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../config/session.php';

verificarAutenticacion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_comprador = $_SESSION['usuario_id'];
    $id_obra = limpiarEntrada($_POST['id_obra']);
    $monto = limpiarEntrada($_POST['monto']);
    $metodo_pago = limpiarEntrada($_POST['metodo_pago']);
    
    try {
        $sql = "INSERT INTO transacciones (id_comprador, id_obra, monto, metodo_pago, estado) VALUES (:id_comprador, :id_obra, :monto, :metodo_pago, 'pendiente')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'id_comprador' => $id_comprador,
            'id_obra' => $id_obra,
            'monto' => $monto,
            'metodo_pago' => $metodo_pago
        ]);
        echo json_encode(["message" => "Pago registrado correctamente"]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error en el pago: " . $e->getMessage()]);
    }
}

?>
