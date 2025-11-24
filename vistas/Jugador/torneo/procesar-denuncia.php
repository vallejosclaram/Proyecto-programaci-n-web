<?php
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../includes/clases/permisos.php'); 
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(['success' => false, 'error' => 'No hay usuario logueado']);
    exit;
}

$id_usuario = $_SESSION["user"]["id"];


if (!Permisos::tienePermiso('Denunciar torneo', $id_usuario)) {
    echo json_encode(['success' => false, 'error' => 'No tenés permiso para denunciar torneos']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$id_reportador = $_SESSION["user"]["id"];
$id_organizador = $input['id_organizador'];
$id_reportado = $input['id_reportado'];
$descripcion = $input['descripcion'];



$sql = "INSERT INTO denuncias (id_reportador, id_reportado, id_organizador, descripcion, fecha_creacion)
        VALUES (:id_reportador, :id_reportado, :id_organizador, :descripcion, NOW())";

$stmt = $conn->prepare($sql);
if ($stmt->execute([
    ':id_reportador' => $id_reportador,
    ':id_reportado' => $id_reportado,
    ':id_organizador' => $id_organizador,
    ':descripcion' => $descripcion
])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'No se pudo guardar la denuncia']);
}
