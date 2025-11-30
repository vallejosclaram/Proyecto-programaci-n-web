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
    o.nombre AS organizador_nombre,
    o.apellido AS organizador_apellido
FROM torneo t
INNER JOIN organizador o ON t.id_organizador = o.id_organizador
INNER JOIN juego j ON t.id_juego = j.id_juego
INNER JOIN tipo_torneo tt ON t.id_tipo = tt.id_tipo
WHERE t.id_torneo NOT IN (
    SELECT st.id_torneo
    FROM solicitud_torneo st
    WHERE st.id_usuario = :jugador_id
)
ORDER BY t.fecha_inicio ASC
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(":jugador_id", $usuario_id, PDO::PARAM_INT);
$stmt->execute();

$torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($torneos, JSON_UNESCAPED_UNICODE);
?>