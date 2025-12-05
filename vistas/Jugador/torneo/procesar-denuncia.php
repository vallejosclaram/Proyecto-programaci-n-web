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

// Obtener datos del request
$input = json_decode(file_get_contents('php://input'), true);
$id_reportador = $id_usuario;
$id_organizador = $input['id_organizador'] ?? null;
$id_reportado = $input['id_reportado'] ?? null;
$id_torneo = $input['id_torneo'] ?? null;
$descripcion = $input['descripcion'] ?? null;

// Validar datos obligatorios
if (empty($id_organizador) || empty($id_reportado) || empty($id_torneo) || empty($descripcion)) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios']);
    exit;
}

// Insertar denuncia en la base de datos
$sql = "INSERT INTO denuncias 
        (id_reportador, id_reportado, id_organizador, descripcion, fecha_creacion, id_torneo)
        VALUES (:id_reportador, :id_reportado, :id_organizador, :descripcion, NOW(), :id_torneo)";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id_reportador' => $id_reportador,
        ':id_reportado' => $id_reportado,
        ':id_organizador' => $id_organizador,
        ':descripcion' => $descripcion,
        ':id_torneo' => $id_torneo
    ]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'No se pudo guardar la denuncia: ' . $e->getMessage()]);
}
