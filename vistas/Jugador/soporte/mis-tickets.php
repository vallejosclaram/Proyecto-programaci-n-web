<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$id_usuario = $_SESSION["user"]["id"];

$sqlTickets = "
    SELECT t.id_ticket, t.asunto, t.descripcion AS ticket_descripcion, t.fecha_creacion,
           r.id_respuesta, r.respuesta, r.fecha_respuesta
    FROM ticket t
    LEFT JOIN respuesta_ticket r ON t.id_ticket = r.id_ticket
    WHERE t.id_usuario = :id_usuario
    ORDER BY t.fecha_creacion DESC, r.fecha_respuesta ASC
";

$stmt = $conn->prepare($sqlTickets);
$stmt->execute(['id_usuario' => $id_usuario]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($tickets);
?>
