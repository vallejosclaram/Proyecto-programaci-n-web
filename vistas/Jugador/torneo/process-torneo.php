<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode([]);
    exit;
}

$usuario_id = $_SESSION["user"]["id"];

// Query para traer los torneos con info completa
$sql = "SELECT 
            t.id_torneo,
            t.id_organizador,
            t.id_juego,
            t.nombre,
            t.descripcion,
            t.fecha_inicio,
            t.fecha_fin,
            t.id_estado,
            t.id_tipo,
            j.nombre AS juego,
            e.descripcion AS tipo_torneo,
            o.nombre AS organizador_nombre,
            o.apellido AS organizador_apellido
        FROM torneo t
        INNER JOIN juego j ON t.id_juego = j.id_juego
        INNER JOIN tipo_torneo e ON t.id_tipo = e.id_tipo
        INNER JOIN organizador o ON t.id_organizador = o.id_organizador
        ORDER BY t.fecha_inicio ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$torneos = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $torneos[] = $row;
}

echo json_encode($torneos);
