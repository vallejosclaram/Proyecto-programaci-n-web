<?php
header("Content-Type: application/json; charset=utf-8");
include 'connection.php';
session_start();

// DEBUG: devolver info útil en caso de fallo
$debug = [];

// VALIDAR SESIÓN
if (!isset($_SESSION['id_usuario'])) {
    // incluir info de cookies para depuración
    $debug['cookies'] = $_COOKIE;
    echo json_encode(["status" => "error", "msg" => "No has iniciado sesión", "debug" => $debug]);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$debug['session_id_usuario'] = $id_usuario;

// RECIBIR ID
$id = $_POST['id_torneo'] ?? null;

if (!$id) {
    echo json_encode(["status" => "error", "msg" => "ID no enviado", "debug" => $debug]);
    exit;
}

// Verificar que el torneo pertenece al organizador de la sesión
try {
    // Obtener id_organizador del usuario en sesión
    $stmtOrg = $conn->prepare('SELECT id_organizador FROM organizador WHERE id_usuario = ? LIMIT 1');
    $stmtOrg->execute([$id_usuario]);
    $org = $stmtOrg->fetch(PDO::FETCH_ASSOC);
    $id_organizador = $org['id_organizador'] ?? null;
    $debug['id_organizador'] = $id_organizador;

    if (!$id_organizador) {
        echo json_encode(["status" => "error", "msg" => "Perfil de organizador no encontrado", "debug" => $debug]);
        exit;
    }

    // Comprobar que el torneo pertenece a este organizador
    $stmtCheck = $conn->prepare('SELECT id_torneo FROM torneo WHERE id_torneo = ? AND id_organizador = ? LIMIT 1');
    $stmtCheck->execute([$id, $id_organizador]);
    $found = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$found) {
        echo json_encode(["status" => "error", "msg" => "No autorizado o torneo no encontrado", "debug" => $debug]);
        exit;
    }

    // ELIMINAR TORNEO
    $stmt = $conn->prepare("DELETE FROM torneo WHERE id_torneo = ? AND id_organizador = ?");
    $ok = $stmt->execute([$id, $id_organizador]);

    echo json_encode([
        "status" => $ok ? "ok" : "error",
        "debug" => $debug
    ]);

} catch (Exception $ex) {
    echo json_encode(["status" => "error", "msg" => "Excepción en servidor: " . $ex->getMessage(), "debug" => $debug]);
}
