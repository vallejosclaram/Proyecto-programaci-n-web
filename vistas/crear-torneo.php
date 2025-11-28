<?php

header("Content-Type: application/json; charset=UTF-8");
include 'connection.php';
session_start();

// Validar sesión real
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["status" => "error", "msg" => "No has iniciado sesión"]);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener id_organizador real
$stmtOrg = $conn->prepare("SELECT id_organizador FROM organizador WHERE id_usuario = ?");
$stmtOrg->execute([$id_usuario]);
$org = $stmtOrg->fetch(PDO::FETCH_ASSOC);

if (!$org) {
    echo json_encode(["status" => "error", "msg" => "Organizador no encontrado"]);
    exit;
}

$id_organizador = $org['id_organizador'];

// Recibir datos
$nombre        = $_POST['nombre'] ?? '';
$juego         = $_POST['juego'] ?? '';
$tipo          = $_POST['tipo'] ?? '';
$fecha_inicio  = $_POST['fechaInscripcion'] ?? '';
$fecha_fin     = $_POST['fechaInscripcionFin'] ?? '';
$estado        = $_POST['estado'] ?? '';

// Validación
if (!$nombre || !$juego || !$tipo || !$fecha_inicio || !$fecha_fin) {
    echo json_encode(["status" => "error", "msg" => "Faltan datos"]);
    exit;
}

// Convertir juego a id_juego
$stmtJuego = $conn->prepare("SELECT id_juego FROM juego WHERE nombre = ?");
$stmtJuego->execute([$juego]);
$g = $stmtJuego->fetch(PDO::FETCH_ASSOC);

if (!$g) {
    echo json_encode(["status" => "error", "msg" => "Juego no encontrado"]);
    exit;
}

$id_juego = $g['id_juego'];

// Convertir tipo a id_tipo
$id_tipo = ($tipo == "individual") ? 1 : 2;

// Convertir estado a id_estado
$id_estado = ($estado == "abierto") ? 1 : 2;

// Descripción por defecto
$descripcion = "Torneo creado por el organizador";

// Insertar torneo
$query = "INSERT INTO torneo
(id_organizador, id_juego, nombre, descripcion, fecha_inicio, fecha_fin, id_estado, id_tipo)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);

if ($stmt->execute([$id_organizador, $id_juego, $nombre, $descripcion, $fecha_inicio, $fecha_fin, $id_estado, $id_tipo])) {

    // RESPUESTA JSON CORRECTA PARA TU JS
    echo json_encode([
        "status" => "ok",
        "nombre" => $nombre,
        "juego" => $juego,
        "fecha_inicio" => $fecha_inicio,
        "fecha_fin" => $fecha_fin,
        "estado" => ($estado == "abierto") ? "Abierto" : "Cerrado",
        "tipo" => ($tipo == "individual") ? "Individual" : "Equipo"
    ]);
} else {
    echo json_encode(["status" => "error", "msg" => "Error al insertar"]);
}

exit;
?>
