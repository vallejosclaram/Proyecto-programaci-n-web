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
    tt.descripcion AS tipo_torneo,
    j.nombre AS juego,
    o.id_organizador,
    o.nombre AS organizador_nombre,
    o.apellido AS organizador_apellido,
    o.organizacion AS organizador_org,
    st.id_solicitud_torneo AS solicitud

FROM torneo t

INNER JOIN tipo_torneo tt 
    ON t.id_tipo = tt.id_tipo

INNER JOIN juego j 
    ON t.id_juego = j.id_juego

INNER JOIN organizador o 
    ON t.id_organizador = o.id_organizador

LEFT JOIN solicitud_torneo st 
    ON st.id_torneo = t.id_torneo
    AND st.id_usuario = :usuario_id

ORDER BY t.fecha_inicio ASC;
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
$stmt->execute();

$torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($torneos, JSON_UNESCAPED_UNICODE);

