<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/security.php';

class OrderController {
    public static function getAllOrders() {
        global $conn;
        $sql = "SELECT * FROM pedidos";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getOrderById($id) {
        global $conn;
        $sql = "SELECT * FROM pedidos WHERE id_pedido = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>
