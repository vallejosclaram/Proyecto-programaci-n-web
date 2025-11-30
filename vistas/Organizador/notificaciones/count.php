<?php
header('Content-Type: application/json; charset=utf-8');
include '../../connection.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['count'=>0]);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// obtener id_organizador
$stmtOrg = $conn->prepare('SELECT id_organizador FROM organizador WHERE id_usuario = ? LIMIT 1');
$stmtOrg->execute([$id_usuario]);
$org = $stmtOrg->fetch(PDO::FETCH_ASSOC);
$id_organizador = $org['id_organizador'] ?? null;

if (!$id_organizador) { echo json_encode(['count'=>0]); exit; }

$stmt = $conn->prepare('SELECT COUNT(*) as cnt FROM solicitud_torneo st JOIN torneo t ON st.id_torneo = t.id_torneo WHERE t.id_organizador = ?');
$stmt->execute([$id_organizador]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$count = (int)($row['cnt'] ?? 0);

echo json_encode(['count'=>$count]);

?>
