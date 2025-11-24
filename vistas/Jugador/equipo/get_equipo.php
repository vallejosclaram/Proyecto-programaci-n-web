<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

if (!isset($_SESSION["user"]["id"])) {
    die(json_encode(["error" => "No hay usuario logueado"]));
}

$usuario_id = $_SESSION["user"]["id"];


    $resultado = [];
    
        //disponibles
        $sql = "SELECT e.id_equipo, e.nombre, e.descripcion, j.nombre AS juego
                FROM equipo e
                JOIN juego j ON j.id_juego = e.id_juego
                WHERE e.estado = 1
                AND e.id_equipo NOT IN (
                    SELECT id_equipo FROM miembros_equipo WHERE id_usuario = :uid
                )";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":uid", $usuario_id);
        $stmt->execute();
        $resultado["disponibles"] = $stmt->fetchAll(PDO::FETCH_ASSOC);


        //solicitudes
        $sql = "SELECT s.fecha_solicitud, j.nombre AS usuario, e.nombre AS equipo
                FROM solicitud_equipo s
                JOIN jugador j ON s.id_usuario = j.id_usuario
                JOIN equipo e ON s.id_equipo = e.id_equipo
                WHERE e.id_capitan_usuario = :uid";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":uid", $usuario_id);
        $stmt->execute();
        $resultado["solicitudes"] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($resultado){
    echo json_encode($resultado);
    } else {
        echo json_encode(['error' => 'Error al cargar los datos']);
    }
    

