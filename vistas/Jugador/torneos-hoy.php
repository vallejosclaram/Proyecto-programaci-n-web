<?php
require_once "../connection.php";

header("Content-Type: application/json; charset=utf-8");

try {
    $sql = "
        SELECT 
            id_torneo,
            nombre,
            descripcion,
            fecha_inicio,
            fecha_fin
        FROM torneo
        WHERE CURDATE() BETWEEN fecha_inicio AND fecha_fin
        ORDER BY fecha_inicio ASC
    ";

    $stmt = $conn->query($sql);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
