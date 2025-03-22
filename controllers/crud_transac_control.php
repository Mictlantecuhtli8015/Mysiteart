<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/security.php';

class TransactionController {
    public static function getAllTransactions() {
        global $conn;
        $sql = "SELECT * FROM transacciones";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTransactionById($id) {
        global $conn;
        $sql = "SELECT * FROM transacciones WHERE id_transaccion = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>