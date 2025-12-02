<?php
include '../../vistas/connection.php';
session_start();

if (!isset($_GET['id_solicitud'])) {
    echo "Solicitud inválida";
    exit;
}

$id_solicitud = $_GET['id_solicitud'];

// 1️⃣ Obtener datos de la solicitud
$sql = "
    SELECT id_solicitud_torneo, id_torneo, id_usuario, id_equipo
    FROM solicitud_torneo
    WHERE id_solicitud_torneo = ?
";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_solicitud]);
$solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$solicitud) {
    echo "Solicitud no encontrada";
    exit;
}

$id_torneo = $solicitud['id_torneo'];
$id_usuario = $solicitud['id_usuario'];
$id_equipo  = $solicitud['id_equipo'];

// 2️⃣ Si la solicitud es de jugador → insertar en torneo_jugador
if ($id_usuario != null && $id_usuario != 0) {

    // Buscar id_jugador con id_usuario
    $stmt = $conn->prepare("SELECT id_jugador FROM jugador WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $id_jugador = $stmt->fetchColumn();

    if (!$id_jugador) {
        echo "Jugador no encontrado";
        exit;
    }

    $stmt = $conn->prepare("
        INSERT INTO torneo_jugador (id_torneo, id_jugador)
        VALUES (?, ?)
    ");
    $stmt->execute([$id_torneo, $id_jugador]);
}

// 3️⃣ Si la solicitud es de equipo → insertar en torneo_equipo
if ($id_equipo != null && $id_equipo != 0) {

    $stmt = $conn->prepare("
        INSERT INTO torneo_equipo (id_torneo, id_equipo)
        VALUES (?, ?)
    ");
    $stmt->execute([$id_torneo, $id_equipo]);
}

// 4️⃣ Borrar solicitud (ya fue procesada)
$stmt = $conn->prepare("DELETE FROM solicitud_torneo WHERE id_solicitud_torneo = ?");
$stmt->execute([$id_solicitud]);

// 5️⃣ Redirigir
header("Location: http://localhost/Proyecto-programaci-n-web/vistas/Organizador/solicitudes.php?ok=1");
exit;
?>
