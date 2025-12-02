<?php
include '../../vistas/connection.php';
session_start();

// Usuario actual logueado
$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_usuario) {
    echo json_encode(["error" => "No autenticado"]);
    exit;
}

// 1️⃣ Ver si es organizador
$stmt = $conn->prepare("
    SELECT id_organizador 
    FROM organizador 
    WHERE id_usuario = ?
");
$stmt->execute([$id_usuario]);
$id_organizador = $stmt->fetchColumn();

if (!$id_organizador) {
    echo json_encode(["data" => [], "msg" => "El usuario no es organizador"]);
    exit;
}

// 2️⃣ Buscar torneos creados
$stmt = $conn->prepare("
    SELECT id_torneo 
    FROM torneo 
    WHERE id_organizador = ?
");
$stmt->execute([$id_organizador]);
$torneos = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (!$torneos) {
    echo json_encode(["data" => [], "msg" => "No tiene torneos"]);
    exit;
}

$placeholders = implode(",", array_fill(0, count($torneos), "?"));

// 3️⃣ Traer solicitudes + jugador + equipo + torneo
$sql = "
    SELECT 
        s.id_solicitud_torneo,
        s.id_torneo,
        s.id_usuario,
        s.id_equipo,
        s.fecha_solicitud,

        -- Datos del jugador
        j.id_jugador,
        CONCAT(j.nombre, ' ', j.apellido) AS jugador_nombre,

        -- Datos del equipo
        e.nombre AS equipo_nombre,

        -- Torneo
        t.nombre AS torneo_nombre

    FROM solicitud_torneo s
    LEFT JOIN jugador j ON j.id_usuario = s.id_usuario
    LEFT JOIN equipo e ON e.id_equipo = s.id_equipo
    INNER JOIN torneo t ON t.id_torneo = s.id_torneo
    WHERE s.id_torneo IN ($placeholders)
    ORDER BY s.fecha_solicitud DESC
";

$stmt2 = $conn->prepare($sql);
$stmt2->execute($torneos);

$data = $stmt2->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["data" => $data]);
?>
