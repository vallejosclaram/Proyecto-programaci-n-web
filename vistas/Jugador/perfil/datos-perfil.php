<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

header("Content-Type: application/json");

// Solo verificamos sesión si realmente lo necesitás
if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

// Traemos el id del perfil desde GET
$id_objetivo = $_GET["id"] ?? null;

if (!$id_objetivo) {
    echo json_encode(["error" => "ID inválido"]);
    exit;
}

// Obtener datos del jugador
$sqlJugador = "
    SELECT 
        j.nombre,
        j.apellido
    FROM jugador j
    WHERE j.id_usuario = :id
";
$stmt = $conn->prepare($sqlJugador);
$stmt->execute([":id" => $id_objetivo]);
$jugador = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener comentarios + nombre del autor
$sqlComentarios = "
    SELECT 
        c.id_comentario,
        c.comentario,
        j.nombre AS autor
    FROM comentario c
    LEFT JOIN jugador j ON j.id_usuario = c.id_autor
    WHERE c.id_objetivo = :id
    ORDER BY c.id_comentario DESC
";
$stmt2 = $conn->prepare($sqlComentarios);
$stmt2->execute([":id" => $id_objetivo]);
$comentarios = $stmt2->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "jugador" => $jugador,
    "comentarios" => $comentarios
]);
