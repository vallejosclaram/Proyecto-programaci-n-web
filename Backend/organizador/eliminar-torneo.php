<?php
header("Content-Type: application/json; charset=utf-8");
include __DIR__ . '/../../vistas/connection.php';
session_start();

/* ============================
   DEBUG (para saber qué llega)
============================ */
$debug = "POST:\n" . print_r($_POST, true);
$debug .= "\nREQUEST:\n" . print_r($_REQUEST, true);
$debug .= "\nINPUT RAW:\n" . file_get_contents("php://input") . "\n";
file_put_contents("debug_eliminar.txt", $debug);

/* ============================
   1) Verificar sesión
============================ */
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["status" => "error", "msg" => "No has iniciado sesión"]);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

/* ============================
   2) Recibir ID del torneo
============================ */
$id = $_POST['id_torneo']
    ?? $_REQUEST['id_torneo']
    ?? (json_decode(file_get_contents("php://input"), true)['id_torneo'] ?? null);

if (!$id) {
    echo json_encode(["status" => "error", "msg" => "ID no enviado"]);
    exit;
}

/* ============================
   3) Obtener id_organizador
============================ */
$stmtOrg = $conn->prepare("SELECT id_organizador FROM organizador WHERE id_usuario = ? LIMIT 1");
$stmtOrg->execute([$id_usuario]);
$org = $stmtOrg->fetch(PDO::FETCH_ASSOC);

if (!$org) {
    echo json_encode(["status" => "error", "msg" => "Organizador no encontrado"]);
    exit;
}

$id_organizador = $org["id_organizador"];

/* ============================
   4) Verificar que el torneo pertenece al organizador
============================ */
$stmtCheck = $conn->prepare("
    SELECT id_torneo 
    FROM torneo 
    WHERE id_torneo = ? AND id_organizador = ? LIMIT 1
");
$stmtCheck->execute([$id, $id_organizador]);

if (!$stmtCheck->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode(["status" => "error", "msg" => "No autorizado o torneo no encontrado"]);
    exit;
}

/* ============================
   5) Eliminar torneo
============================ */
$stmtDelete = $conn->prepare("
    DELETE FROM torneo 
    WHERE id_torneo = ? AND id_organizador = ?
");

$ok = $stmtDelete->execute([$id, $id_organizador]);

echo json_encode([
    "status" => $ok ? "ok" : "error",
    "msg" => $ok ? "Torneo eliminado" : "Error al eliminar"
]);
exit;
