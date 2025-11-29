<?php
header("Content-Type: application/json");
include 'connection.php';
session_start();

$id = $_POST['id'] ?? null;
$nombre = $_POST['nombre'] ?? '';
$juego = $_POST['juego'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$inicio = $_POST['inicio'] ?? '';
$fin = $_POST['fin'] ?? '';
$estado = $_POST['estado'] ?? '';

if (!$id) {
    echo json_encode(["status"=>"error","msg"=>"ID faltante"]);
    exit;
}

$stmtJuego = $conn->prepare("SELECT id_juego FROM juego WHERE nombre = ?");
$stmtJuego->execute([$juego]);
$j = $stmtJuego->fetch(PDO::FETCH_ASSOC);

$id_juego = $j['id_juego'];

$id_tipo = ($tipo == "Individual") ? 1 : 2;

$query = $conn->prepare("
  UPDATE torneo SET
    nombre = ?,
    id_juego = ?,
    fecha_inicio = ?,
    fecha_fin = ?,
    id_estado = ?,
    id_tipo = ?
  WHERE id_torneo = ?
");

$ok = $query->execute([$nombre, $id_juego, $inicio, $fin, $estado, $id_tipo, $id]);

echo json_encode([
  "status" => $ok ? "ok" : "error"
]);
