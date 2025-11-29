<?php
require_once "../connection.php";

header("Content-Type: application/json; charset=utf-8");

try {
    $sql = "
        SELECT 
            id_jugador,
            nombre,
            apellido,
            pais,
            puntaje
        FROM jugador
        ORDER BY puntaje DESC
        LIMIT 10
    ";

    $stmt = $conn->query($sql);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
