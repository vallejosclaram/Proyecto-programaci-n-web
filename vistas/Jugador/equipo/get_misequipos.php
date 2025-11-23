<?php
header("Content-Type: application/json");
require_once(__DIR__ . "/../../connection.php");
session_start();

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$usuario_id = $_SESSION["user"]["id"];

/* ===========================
   1. EQUIPOS DONDE ES CAPITANA
   =========================== */
$sqlCapitana = "
SELECT e.id_equipo AS id,
       e.nombre,
       e.descripcion,
       j.nombre AS juego,
       'capitana' AS rol
FROM equipo e
JOIN juego j ON j.id_juego = e.id_juego
WHERE e.id_capitan_usuario = :uid
";
$stmt = $conn->prepare($sqlCapitana);
$stmt->bindValue(":uid", $usuario_id, PDO::PARAM_INT);
$stmt->execute();
$equiposCapitana = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* ===========================
   2. EQUIPOS DONDE ES MIEMBRO
   =========================== */
$sqlMiembro = "
SELECT e.id_equipo AS id,
       e.nombre,
       e.descripcion,
       j.nombre AS juego,
       m.rol_en_equipo AS rol
FROM miembros_equipo m
JOIN equipo e ON e.id_equipo = m.id_equipo
JOIN juego j ON j.id_juego = e.id_juego
WHERE m.id_usuario = :uid
";
$stmt = $conn->prepare($sqlMiembro);
$stmt->bindValue(":uid", $usuario_id, PDO::PARAM_INT);
$stmt->execute();
$equiposMiembro = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* ===========================
   3. UNIFICAR SIN DUPLICADOS
   =========================== */
$misEquipos = [];

foreach ($equiposCapitana as $e) {
    $misEquipos[$e["id"]] = $e;
}

foreach ($equiposMiembro as $e) {
    $misEquipos[$e["id"]] = $e;
}


/* ===========================
   4. RESPUESTA FINAL
   =========================== */
echo json_encode([
    "misEquipos" => array_values($misEquipos)
]);

