<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

if (!isset($_GET["id"])) {
    die(json_encode(["error" => "Falta ID"]));
}

$id = intval($_GET["id"]);

$sql = "
SELECT e.*, j.nombre AS juego, u.nombre AS capitan
FROM equipo e
JOIN juego j ON j.id_juego = e.id_juego
JOIN usuarios u ON u.id_usuario = e.id_capitan_usuario
WHERE e.id_equipo = :id
";

$stmt = $conn->prepare($sql);
$stmt->bindValue(":id", $id);
$stmt->execute();

$data = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($data);
?>