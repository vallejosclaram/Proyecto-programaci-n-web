<?php
require_once("../../connection.php");

$idEquipo = $_GET["id"] ?? null;

if (!$idEquipo) {
    echo json_encode(["error" => "Falta id de equipo"]);
    exit;
}

$sql = "SELECT 
    m.id_usuario,
    j.nombre
    FROM miembros_equipo m
    INNER JOIN jugador j ON m.id_usuario = j.id_usuario
    WHERE m.id_equipo = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([":id" => $idEquipo]);
$miembros = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "miembros" => $miembros
]);
