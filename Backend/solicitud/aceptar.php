<?php
include '../../connection.php';
session_start();

$id_torneo = $_GET['id_torneo'] ?? null;
$id_jugador = $_GET['id_jugador'] ?? null;

if (!$id_torneo || !$id_jugador) {
    http_response_code(400);
    echo "Faltan datos";
    exit;
}

// 1. Verificar si ya está inscrito
$stmt = $conn->prepare("
    SELECT 1 FROM torneo_jugador 
    WHERE id_torneo = ? AND id_jugador = ?
    LIMIT 1
");
$stmt->execute([$id_torneo, $id_jugador]);

if ($stmt->fetch()) {
    // Ya existe → no insertar
    header("Location: ../../vistas/Organizador/solicitudes.php?ok=ya_inscripto");
    exit;
}

// 2. Insertar solo si no existe
$stmt2 = $conn->prepare("
    INSERT INTO torneo_jugador (id_torneo, id_jugador, fecha_inscripcion)
    VALUES (?, ?, NOW())
");
$stmt2->execute([$id_torneo, $id_jugador]);

header("Location: ../../vistas/Organizador/solicitudes.php?ok=1");
exit;
?>
