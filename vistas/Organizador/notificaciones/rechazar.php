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
    // verificar que la solicitud pertenece a un torneo del organizador
    $stmt = $conn->prepare('SELECT st.id_solicitud_torneo FROM solicitud_torneo st JOIN torneo t ON st.id_torneo = t.id_torneo JOIN organizador o ON t.id_organizador = o.id_organizador WHERE st.id_solicitud_torneo = ? AND o.id_usuario = ? LIMIT 1');
    $stmt->execute([$id_solicitud, $id_usuario]);
    $ok = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$ok) { echo json_encode(['status'=>'error','msg'=>'No autorizado']); exit; }

    $stmtDel = $conn->prepare('DELETE FROM solicitud_torneo WHERE id_solicitud_torneo = ?');
    $stmtDel->execute([$id_solicitud]);

    echo json_encode(['status'=>'ok']);
} catch(Exception $ex){ echo json_encode(['status'=>'error','msg'=>$ex->getMessage()]); }

?>
