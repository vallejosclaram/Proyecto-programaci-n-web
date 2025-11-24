<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$usuario_id = $_SESSION["user"]["id"];

$sql = "
SELECT 
    t.id_torneo,
    t.nombre,
    t.descripcion,
    t.fecha_inicio,
    t.fecha_fin,
    t.id_tipo,
    tt.descripcion AS tipo_torneo,
    j.nombre AS juego,
    o.id_organizador AS organizador,
    IF(it.id_jugador IS NULL, 0, 1) AS inscripto
FROM torneo t
INNER JOIN organizador o ON t.id_organizador = o.id_organizador
INNER JOIN juego j ON t.id_juego = j.id_juego
INNER JOIN tipo_torneo tt ON t.id_tipo = tt.id_tipo
LEFT JOIN inscripcion_torneo it 
    ON it.id_torneo = t.id_torneo
    AND it.id_jugador = :jugador_id
WHERE it.id_torneo IS NULL
ORDER BY t.fecha_inicio ASC
";


$stmt = $conn->prepare($sql);
$stmt->bindParam(":jugador_id", $usuario_id, PDO::PARAM_INT);
$stmt->execute();

$torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($torneos, JSON_UNESCAPED_UNICODE);
