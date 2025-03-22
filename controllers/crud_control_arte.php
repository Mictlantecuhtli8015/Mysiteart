<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/security.php';

class ArtController {
    public static function getAllArtworks() {
        global $conn;
        $sql = "SELECT * FROM obras";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getArtworkById($id) {
        global $conn;
        $sql = "SELECT * FROM obras WHERE id_obra = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>
