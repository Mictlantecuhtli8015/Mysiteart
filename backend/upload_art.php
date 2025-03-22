<?php

require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../config/session.php';

verificarAutenticacion();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen'])) {
    $id_artista = $_SESSION['usuario_id'];
    $titulo = limpiarEntrada($_POST['titulo']);
    $descripcion = limpiarEntrada($_POST['descripcion']);
    $precio = limpiarEntrada($_POST['precio']);
    
    $imagen = $_FILES['imagen'];
    $rutaDestino = __DIR__ . '/../assets/img/' . basename($imagen['name']);
    
    if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
        try {
            $sql = "INSERT INTO obras (id_artista, titulo, descripcion, precio) VALUES (:id_artista, :titulo, :descripcion, :precio)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'id_artista' => $id_artista,
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'precio' => $precio
            ]);
            echo json_encode(["message" => "Obra subida con éxito"]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al subir la obra: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "Error al subir la imagen"]);
    }
}

?>
