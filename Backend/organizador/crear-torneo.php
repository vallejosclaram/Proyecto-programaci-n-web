<?php

header("Content-Type: application/json; charset=UTF-8");
include '../../vistas/connection.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["status" => "error", "msg" => "No has iniciado sesión"]);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener id_organizador
$stmtOrg = $conn->prepare("SELECT id_organizador FROM organizador WHERE id_usuario = ?");
$stmtOrg->execute([$id_usuario]);
$org = $stmtOrg->fetch(PDO::FETCH_ASSOC);

if (!$org) {
    echo json_encode(["status" => "error", "msg" => "Organizador no encontrado"]);
    exit;
}

$id_organizador = $org['id_organizador'];

// Datos recibidos
$nombre        = $_POST['nombre'] ?? '';
$juego         = $_POST['juego'] ?? '';
$tipo          = $_POST['tipo'] ?? '';
$fecha_inicio  = $_POST['fechaInscripcion'] ?? '';
$fecha_fin     = $_POST['fechaInscripcionFin'] ?? '';

// Validación básica (sin estado)
if (!$nombre || !$juego || !$tipo || !$fecha_inicio || !$fecha_fin) {
    echo json_encode(["status" => "error", "msg" => "Faltan datos"]);
    exit;
}

// ID del juego
$stmtJuego = $conn->prepare("SELECT id_juego FROM juego WHERE nombre = ?");
$stmtJuego->execute([$juego]);
$g = $stmtJuego->fetch(PDO::FETCH_ASSOC);

if (!$g) {
    echo json_encode(["status" => "error", "msg" => "Juego no encontrado"]);
    exit;
}

$id_juego = $g['id_juego'];

// Tipo: 1=Individual, 2=Equipo
$id_tipo = ($tipo === "individual") ? 1 : 2;

// Descripción automática
$descripcion = "Torneo creado por el organizador";

// INSERT (estado queda en 'pendiente' por defecto)
$query = "INSERT INTO solicitud_creacion_torneo
(id_usuario, nombre, descripcion, id_juego, fecha_inicio, fecha_fin, id_tipo)
VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);

$stmt->execute([
    $id_usuario,
    $nombre,
    $descripcion,
    $id_juego,
    $fecha_inicio,
    $fecha_fin,
    $id_tipo
]);

echo json_encode([
    "status" => "ok",
    "msg" => "Solicitud enviada correctamente. Queda en estado PENDIENTE."
]);

?>

