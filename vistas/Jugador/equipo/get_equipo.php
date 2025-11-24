<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

if (!isset($_SESSION["user"]["id"])) {
    die(json_encode(["error" => "No hay usuario logueado"]));
}

$usuario_id = $_SESSION["user"]["id"];

try {
    $resultado = [];
    
    $sql = "
        SELECT e.id_equipo, e.nombre, e.descripcion, j.nombre AS juego
        FROM equipo e
        JOIN juego j ON j.id_juego = e.id_juego
        WHERE e.estado = 1
          AND e.id_equipo NOT IN (
              SELECT id_equipo
              FROM miembros_equipo
              WHERE id_usuario = :uid
          )
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":uid", $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $resultado['disponibles'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultado);

} catch (Exception $e) {
    echo json_encode(['error' => 'Error al cargar los equipos']);
}
