<?php
header("Content-Type: application/json");
require_once(__DIR__ . "/../../connection.php");
session_start();

// Validar login
if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$usuario_id = $_SESSION["user"]["id"];

// Recibir JSON
$data = json_decode(file_get_contents("php://input"), true);

$id = $data["id"] ?? null;
$nombre = trim($data["nombre"] ?? "");
$descripcion = trim($data["descripcion"] ?? "");

// Validaciones básicas
if (!$id || $nombre === "") {
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

// Verificar que este usuario sea capitan del equipo
$sqlCheck = "
    SELECT id_equipo 
    FROM equipo 
    WHERE id_equipo = :id AND id_capitan_usuario = :uid
";

$stmt = $conn->prepare($sqlCheck);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
stmt->bindValue(":uid", $usuario_id, PDO::PARAM_INT);
$stmt->execute();

$esCapitana = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$esCapitana) {
    echo json_encode(["error" => "No tenés permiso para editar este equipo"]);
    exit;
}

// Actualizamos datos
$sqlUpdate = "
    UPDATE equipo
    SET nombre = :nombre,
        descripcion = :descripcion
    WHERE id_equipo = :id
";

$stmt = $conn->prepare($sqlUpdate);
$stmt->bindValue(":nombre", $nombre);
$stmt->bindValue(":descripcion", $descripcion);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);

$stmt->execute();

// Respuesta
echo json_encode([
    "success" => true,
    "message" => "Equipo actualizado correctamente"
]);
