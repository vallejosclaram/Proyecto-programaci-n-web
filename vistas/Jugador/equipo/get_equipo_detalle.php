<?php
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../includes/clases/permisos.php'); 
session_start();

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$usuario_id = $_SESSION["user"]["id"];

if (!Permisos::tienePermiso("Crear equipo", $usuario_id)) {
    echo json_encode(["error" => "No tenés permiso para crear un equipo"]);
    exit;
}

// validar id
if (!isset($_GET["id"])) {
    echo json_encode(["error" => "Falta ID"]);
    exit;
}

$id = intval($_GET["id"]);

$sql = "
    SELECT 
    e.id_equipo,
    e.nombre AS nombre_equipo,
    e.descripcion,
    e.id_juego,
    j.nombre AS nombre_juego,
    
    ju.id_usuario AS id_capitan,
    ju.nombre AS nombre_capitan,
    ju.apellido AS apellido_capitan,
    
    GROUP_CONCAT(me.id_usuario) AS miembros_ids
FROM equipo e
-- Traer el juego
JOIN juego j ON j.id_juego = e.id_juego
-- Traer el capitán
JOIN jugador ju ON ju.id_usuario = e.id_capitan_usuario
-- Traer miembros del equipo (puede ser 0 miembros, LEFT JOIN)
LEFT JOIN miembros_equipo me ON me.id_equipo = e.id_equipo
WHERE e.id_equipo = :id_equipo
  AND e.estado = 1
GROUP BY e.id_equipo, e.nombre, e.descripcion, e.id_juego, j.nombre, ju.id_usuario, ju.nombre, ju.apellido

";

$stmt = $conn->prepare($sql);
$stmt->bindValue(":id_equipo", $id, PDO::PARAM_INT);
$stmt->execute();

$detalles = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$detalles) {
    echo json_encode(["error" => "No existe el equipo o no está disponible"]);
    exit;
}

echo json_encode(["equipo" => $detalles]);
exit;
