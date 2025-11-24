<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

if (!isset($_SESSION["user"]["id"])) {
    die("Error: no hay usuario logueado.");
}

$usuario_id = $_SESSION["user"]["id"];

//búsqueda
if (isset($_GET["q"])) {
    $busqueda = trim($_GET["q"]);
} else {
    $busqueda = "";
}

if (isset($_GET["juego"])) {
    $id_juego = trim($_GET["juego"]);
} else {
    $id_juego = "";
}

$filters = "";
$params = [];

if ($q !== "") {
    $filters .= " AND (e.nombre LIKE :buscar OR j.nombre LIKE :buscar) ";
    $params[":buscar"] = "%$q%";
}

if ($juego !== "") {
    $filters .= " AND j.nombre = :juego ";
    $params[":juego"] = $juego;
}



//mostrar mis equipo
$sqlMisEquipos = "
SELECT e.*, j.nombre AS juego
FROM equipo e
JOIN miembros_equipo m ON m.id_equipo = e.id_equipo
JOIN juego j ON j.id_juego = e.id_juego
WHERE m.id_usuario = :usuario
$filters
";

$stmt = $conn->prepare($sqlMisEquipos);
$stmt->bindValue(":usuario", $id_usuario);
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->execute();
$misEquipos = $stmt->fetchAll(PDO::FETCH_ASSOC);


//mostrar qeuipo disponicle
$sqlDisponibles = "
SELECT e.*, j.nombre AS juego
FROM equipo e
JOIN juego j ON j.id_juego = e.id_juego
WHERE e.estado = 1 
AND e.id_equipo NOT IN (
    SELECT id_equipo FROM miembros_equipo WHERE id_usuario = :usuario
)
$filters
";

$stmt = $conn->prepare($sqlDisponibles);
$stmt->bindValue(":usuario", $id_usuario);
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->execute();
$equiposDisponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);


//mostrar solicitudes
$sqlSolicitudes = "
SELECT s.*, e.nombre AS equipo, u.nombre AS usuario
FROM solicitudes s
JOIN equipo e ON e.id_equipo = s.id_equipo
JOIN usuarios u ON u.id_usuario = s.id_usuario
WHERE e.id_capitan_usuario = :capitan
AND e.estado = 1
";

$stmt = $conn->prepare($sqlSolicitudes);
$stmt->bindValue(":capitan", $id_usuario);
$stmt->execute();
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "misEquipos"   => $misEquipos,
    "disponibles"  => $equiposDisponibles,
    "solicitudes"  => $solicitudes
]);

