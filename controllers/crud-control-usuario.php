<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/security.php';

class UserController {
    public static function getAllUsers() {
        global $conn, $key;
        $sql = "SELECT id_usuario, nombre, correo, tipo_usuario FROM usuarios";
        $stmt = $conn->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($usuarios as &$usuario) {
            $usuario['correo'] = decryptData($usuario['correo'], $key);
        }
        return $usuarios;
    }

    public static function getUserById($id) {
        global $conn, $key;
        $sql = "SELECT id_usuario, nombre, correo, tipo_usuario FROM usuarios WHERE id_usuario = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario) {
            $usuario['correo'] = decryptData($usuario['correo'], $key);
        }
        return $usuario;
    }
}

?>