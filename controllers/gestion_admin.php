<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/security.php';

class AdminController {
    public static function getAllAdmins() {
        global $conn;
        $sql = "SELECT * FROM usuarios WHERE tipo_usuario = 'admin'";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
