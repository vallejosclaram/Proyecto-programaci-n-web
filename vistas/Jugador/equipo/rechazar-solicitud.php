<?php
require_once("../../connection.php");
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$idSolicitud = $data["solicitud"] ?? null;

if (!$idSolicitud) {
    echo json_encode(["error" => "Solicitud no recibida"]);
    exit;
}

try {
    $sql = "DELETE FROM solicitud_equipo WHERE id_solicitud = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([":id" => $idSolicitud]);

    echo json_encode(["ok" => true]);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
