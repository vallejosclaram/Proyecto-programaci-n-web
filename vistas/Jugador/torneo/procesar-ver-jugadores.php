<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

if (!isset($_POST['id_torneo'])) {
    echo json_encode(['error' => 'No se recibió id_torneo']);
    exit;
}

$id_torneo = $_POST['id_torneo'];

try {
    $sql = "
        SELECT j.nombre AS nombre, eq.nombre AS equipo
        FROM miembros_equipo me
        JOIN usuario u ON u.id_usuario = me.id_usuario
        JOIN jugador j ON j.id_usuario = u.id_usuario
        JOIN equipo eq ON eq.id_equipo = me.id_equipo
        WHERE eq.id_equipo IN (
            SELECT id_equipo 
            FROM torneo_equipo 
            WHERE id_torneo = :id_torneo
        )
        ORDER BY nombre ASC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_torneo', $id_torneo);
    $stmt->execute();
    $jugadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($jugadores);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
