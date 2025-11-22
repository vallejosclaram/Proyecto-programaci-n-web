<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

if (!isset($_SESSION["user"]["id"])) {
    die(json_encode(["error" => "No hay usuario logueado"]));
}

$usuario_id = $_SESSION["user"]["id"];


$id = isset($_GET["id"]) ? intval($_GET["id"]) : null;
$q = isset($_GET["q"]) ? "%".$_GET["q"]."%" : "%";  
$id_juego = isset($_GET["juego"]) ? intval($_GET["juego"]) : null;

try {
    $resultado = [];

   
    if ($id) {
        $sql = "
            SELECT e.*, j.nombre AS juego, u.nombre AS capitan, ee.descripcion AS estado
            FROM equipo e
            JOIN juego j ON j.id_juego = e.id_juego
            JOIN usuarios u ON u.id_usuario = e.id_capitan_usuario
            JOIN estado_equipo ee ON ee.id_estado = e.estado
            WHERE e.id_equipo = :id
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $resultado['equipo'] = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    $sql = "
        SELECT e.id_equipo, e.nombre, e.descripcion, j.nombre AS juego
        FROM equipo e
        JOIN juego j ON j.id_juego = e.id_juego
        WHERE e.estado = 1
          AND e.nombre LIKE :q
    ";
    if ($id_juego) {
        $sql .= " AND e.id_juego = :id_juego";
    }
    $sql .= " ORDER BY e.nombre ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':q', $q);
    if ($id_juego) {
        $stmt->bindValue(':id_juego', $id_juego);
    }
    $stmt->execute();
    $resultado['disponibles'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultado);

} catch (Exception $e) {
    echo json_encode(['error' => 'Error al cargar los equipos']);
}
