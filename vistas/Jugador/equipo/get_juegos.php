<?php
require_once(__DIR__ . '/../../connection.php');

header("Content-Type: application/json");

try {
    $stmt = $conn->query("SELECT id_juego, nombre FROM juego ORDER BY nombre ASC");
    $juegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($juegos);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
