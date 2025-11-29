<?php
header("Content-Type: application/json");
include 'connection.php';
session_start();

if (!isset($_POST['id_torneo'])) {
    echo json_encode(["status" => "error", "msg" => "ID no enviado"]);
    exit;
}

$id = $_POST['id_torneo'];

/* ==========================================================
   1. DATOS DEL TORNEO
   ========================================================== */

$stmt = $conn->prepare("
  SELECT 
    t.id_torneo,
    t.nombre AS nombre_torneo,
    t.fecha_inicio,
    t.fecha_fin,
    j.nombre AS juego,
    e.descripcion AS estado,
    tt.descripcion AS tipo,
    CONCAT(o.nombre, ' ', o.apellido) AS organizador
  FROM torneo t
  JOIN juego j ON t.id_juego = j.id_juego
  JOIN estado_torneo e ON t.id_estado = e.id_estado
  JOIN tipo_torneo tt ON t.id_tipo = tt.id_tipo
  JOIN organizador o ON t.id_organizador = o.id_organizador
  WHERE t.id_torneo = ?
");

$stmt->execute([$id]);
$torneo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$torneo) {
    echo json_encode(["status" => "error", "msg" => "Torneo no encontrado"]);
    exit;
}

/* ==========================================================
   2. SEGÚN EL TIPO → OBTENER JUGADORES O EQUIPOS
   ========================================================== */

$lista = [];

if ($torneo["tipo"] === "Individual") {

    // JUGADORES INDIVIDUALES
    $stmt2 = $conn->prepare("
        SELECT j.nombre, j.apellido
        FROM torneo_jugador tj
        JOIN jugador j ON tj.id_jugador = j.id_jugador
        WHERE tj.id_torneo = ?
    ");
    $stmt2->execute([$id]);

    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        $lista[] = $row["nombre"] . " " . $row["apellido"];
    }

} else {

    /* ==========================================================
       TORNEOS POR EQUIPO → obtener equipos + jugadores
       ========================================================== */

    // 1️⃣ Equipos del torneo
    $stmt3 = $conn->prepare("
        SELECT e.id_equipo, e.nombre AS equipo
        FROM torneo_equipo te
        JOIN equipo e ON te.id_equipo = e.id_equipo
        WHERE te.id_torneo = ?
    ");
    $stmt3->execute([$id]);
    $equipos = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    // 2️⃣ Para cada equipo → obtener miembros
    foreach ($equipos as $eq) {

        $stmt4 = $conn->prepare("
            SELECT u.nombre, u.email
            FROM miembros_equipo me
            JOIN usuario u ON me.id_usuario = u.id_usuario
            WHERE me.id_equipo = ?
        ");
        $stmt4->execute([$eq['id_equipo']]);
        $jugadores = $stmt4->fetchAll(PDO::FETCH_ASSOC);

        $lista[] = [
            "equipo" => $eq["equipo"],
            "jugadores" => $jugadores
        ];
    }
}

echo json_encode([
    "status" => "ok",
    "data" => $torneo,
    "detalle" => $lista
]);
?>
