<?php
include("conexion.php");

// Recibir datos del formulario (via POST)
$nombre = $_POST['nombre'] ?? null;
$id_capitan = $_POST['id_capitan_usuario'] ?? null;
$id_juego = $_POST['id_juego'] ?? null;
$descripcion = $_POST['descripcion'] ?? null;

if (!$nombre || !$id_capitan || !$id_juego) {
    echo json_encode(["estado" => "error", "mensaje" => "Datos incompletos"]);
    exit;
}

// Insertar equipo
$sql = "INSERT INTO equipo (nombre, id_capitan_usuario, id_juego, descripcion, estado)
        VALUES (?, ?, ?, ?, 1)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("siis", $nombre, $id_capitan, $id_juego, $descripcion);

if ($stmt->execute()) {
    echo json_encode(["estado" => "ok", "mensaje" => "Equipo creado correctamente"]);
} else {
    echo json_encode(["estado" => "error", "mensaje" => "Error al crear equipo"]);
}

$stmt->close();
$conexion->close();
?>
