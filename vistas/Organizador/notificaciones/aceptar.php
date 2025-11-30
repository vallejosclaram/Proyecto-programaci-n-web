<?php
header('Content-Type: application/json; charset=utf-8');
include '../../connection.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['status'=>'error','msg'=>'No auth']); exit;
}

$id_usuario = $_SESSION['id_usuario'];

$id_solicitud = $_POST['id_solicitud'] ?? null;
if (!$id_solicitud) { echo json_encode(['status'=>'error','msg'=>'No id']); exit; }

try{
    // obtener la solicitud
    $stmt = $conn->prepare('SELECT * FROM solicitud_torneo WHERE id_solicitud_torneo = ? LIMIT 1');
    $stmt->execute([$id_solicitud]);
    $sol = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$sol) { echo json_encode(['status'=>'error','msg'=>'Solicitud no encontrada']); exit; }

    // verificar que el torneo pertenece al organizador en sesión
    $stmtOrg = $conn->prepare('SELECT id_organizador FROM organizador WHERE id_usuario = ? LIMIT 1');
    $stmtOrg->execute([$id_usuario]);
    $org = $stmtOrg->fetch(PDO::FETCH_ASSOC);
    $id_organizador = $org['id_organizador'] ?? null;
    if (!$id_organizador) { echo json_encode(['status'=>'error','msg'=>'No eres organizador']); exit; }

    $stmtT = $conn->prepare('SELECT id_torneo, id_tipo FROM torneo WHERE id_torneo = ? AND id_organizador = ? LIMIT 1');
    $stmtT->execute([$sol['id_torneo'], $id_organizador]);
    $t = $stmtT->fetch(PDO::FETCH_ASSOC);
    if (!$t) { echo json_encode(['status'=>'error','msg'=>'Torneo no encontrado o no autorizado']); exit; }

    // Si es individual, intentar inscribir al jugador automáticamente
    if ((int)$t['id_tipo'] === 1) {
        // buscar id_jugador por id_usuario
        $stmtJ = $conn->prepare('SELECT id_jugador FROM jugador WHERE id_usuario = ? LIMIT 1');
        $stmtJ->execute([$sol['id_usuario']]);
        $j = $stmtJ->fetch(PDO::FETCH_ASSOC);
        if ($j) {
            // insertar en torneo_jugador
            $stmtIns = $conn->prepare('INSERT INTO torneo_jugador (id_torneo, id_jugador) VALUES (?, ?)');
            $stmtIns->execute([$t['id_torneo'], $j['id_jugador']]);
        }
    }

    // eliminar la solicitud tras aceptar
    $stmtDel = $conn->prepare('DELETE FROM solicitud_torneo WHERE id_solicitud_torneo = ?');
    $stmtDel->execute([$id_solicitud]);

    echo json_encode(['status'=>'ok']);
} catch(Exception $ex){
    echo json_encode(['status'=>'error','msg'=>$ex->getMessage()]);
}

?>
