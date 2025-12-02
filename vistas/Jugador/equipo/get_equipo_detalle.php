<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$usuario_id = $_SESSION["user"]["id"];

// validar id
if (!isset($_GET["id"])) {
    echo json_encode(["error" => "Falta ID"]);
    exit;
}

$id = intval($_GET["id"]);

$sql = "
    SELECT 
        e.id_equipo AS id,
        e.nombre,
        e.descripcion,
        j.nombre AS juego,
        j.nombre AS capitan
    FROM equipo e
    JOIN juego j ON j.id_juego = e.id_juego
    JOIN usuario u ON u.id_usuario = e.id_capitan_usuario
    WHERE e.estado = 1
      AND e.id_equipo = :id
      AND e.id_equipo NOT IN (
            SELECT id_equipo 
            FROM miembros_equipo 
            WHERE id_usuario = :uid
      )
";

$stmt = $conn->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->bindValue(":uid", $usuario_id, PDO::PARAM_INT);
$stmt->execute();

$detalles = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$detalles) {
    echo json_encode(["error" => "No existe el equipo o no está disponible"]);
    exit;
}

echo json_encode(["equipo" => $detalles]);
exit;
