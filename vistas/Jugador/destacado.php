<?php
require_once "../connection.php";

header("Content-Type: application/json; charset=utf-8");

try {
    $sql = "
    SELECT 
        u.id_usuario AS usuario_id,
        j.id_jugador AS jugador_id,
        j.nombre,
        j.apellido,
        j.pais,
        j.puntaje
    FROM jugador j
    JOIN usuario u ON u.id_usuario = j.id_usuario
    ORDER BY j.puntaje DESC
    LIMIT 10
    ";

    $stmt = $conn->query($sql);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}