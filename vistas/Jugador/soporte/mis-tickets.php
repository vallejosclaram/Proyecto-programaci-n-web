<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION["id_usuario"])) {
    die("Error: no hay usuario logueado.");
}

$usuario_id = $_SESSION["id_usuario"];
$rol = $_SESSION["rol"] ?? '';
$nombre = $_SESSION["nombre"] ?? '';

$sqlTickets = "
    SELECT t.id_ticket, t.asunto, t.descripcion AS ticket_descripcion, t.fecha_creacion,
           r.id_respuesta, r.respuesta, r.fecha_respuesta
    FROM ticket t
    LEFT JOIN respuesta_ticket r ON t.id_ticket = r.id_ticket
    WHERE t.id_usuario = :usuario_id
    ORDER BY t.fecha_creacion DESC, r.fecha_respuesta ASC
";

$stmt = $conn->prepare($sqlTickets);
$stmt->execute(['usuario_id' => $usuario_id]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($tickets);
?>
