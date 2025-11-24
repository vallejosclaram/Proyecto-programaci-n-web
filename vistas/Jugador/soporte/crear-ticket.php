<?php
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../includes/clases/permisos.php');
session_start();

header('Content-Type: application/json');


if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$id_usuario = $_SESSION["user"]["id"];


if (!Permisos::tienePermiso("Crear ticket", $id_usuario)) {
    echo json_encode(["error" => "No tenés permiso para crear un ticket"]);
    exit;
}


$input = json_decode(file_get_contents('php://input'), true);

$asunto = $input['asunto'];
$descripcion = $input['descripcion'];



if ($asunto === "" || $descripcion === "") {
    echo json_encode(["error" => "Asunto y descripción no pueden estar vacíos"]);
    exit;
}


$sql = "INSERT INTO ticket (id_usuario, asunto, descripcion, fecha_creacion)
        VALUES (:id_usuario, :asunto, :descripcion, NOW())";

$stmt = $conn->prepare($sql);

try {
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':asunto' => $asunto,
        ':descripcion' => $descripcion
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
