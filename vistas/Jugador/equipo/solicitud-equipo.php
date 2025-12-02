<?php
require_once("../../connection.php");
session_start();

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$usuario_id = $_SESSION["user"]["id"];
$data = json_decode(file_get_contents("php://input"), true);
$idEquipo = $data["equipo"] ?? null;

if (!$idEquipo) {
    echo json_encode(["error" => "Equipo no recibido"]);
    exit;
}

// verificar queno haya una solicitud previa
$sql_check = "SELECT 1 FROM solicitud_equipo WHERE id_usuario = :u AND id_equipo = :e";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->execute([":u" => $usuario_id, ":e" => $idEquipo]);

if ($stmt_check->fetch()) {
    echo json_encode(["error" => "Ya enviaste una solicitud a este equipo"]);
    exit;
}

try {
    $sql = "INSERT INTO solicitud_equipo (id_usuario, id_equipo, fecha_solicitud)
            VALUES (:u, :e, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":u" => $usuario_id,
        ":e" => $idEquipo
    ]);

    echo json_encode(["ok" => true]);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
