<?php

header("Content-Type: application/json; charset=UTF-8");
include 'connection.php';
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
$estado        = $_POST['estado'] ?? '';

// Validación básica
if (!$nombre || !$juego || !$tipo || !$fecha_inicio || !$fecha_fin || !$estado) {
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

// Estado: llega directamente como ID (1,2,3)
$id_estado = intval($estado);

if (!in_array($id_estado, [1,2,3])) {
    echo json_encode(["status" => "error", "msg" => "Estado inválido"]);
    exit;
}

// Estado para la card JS
$estadoTexto = match ($id_estado) {
    1 => "Activo",
    2 => "Cerrado",
    3 => "Bloqueado",
    default => "Desconocido"
};

$descripcion = "Torneo creado por el organizador";

$query = "INSERT INTO torneo
(id_organizador, id_juego, nombre, descripcion, fecha_inicio, fecha_fin, id_estado, id_tipo)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);

if ($stmt->execute([$id_organizador, $id_juego, $nombre, $descripcion, $fecha_inicio, $fecha_fin, $id_estado, $id_tipo])) {
    
    $nuevoID = $conn->lastInsertId(); 

    echo json_encode([
        "status" => "ok",
        "id_torneo" => $nuevoID, 
        "nombre" => $nombre,
        "juego" => $juego,
        "fecha_inicio" => $fecha_inicio,
        "fecha_fin" => $fecha_fin,
        "estado" => $estadoTexto,
        "tipo" => ($tipo == "individual") ? "Individual" : "Equipo"
    ]);

} else {
    echo json_encode(["status" => "error", "msg" => "Error al insertar"]);
}

exit;
?>
