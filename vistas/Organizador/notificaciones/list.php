<?php
header('Content-Type: application/json; charset=utf-8');
include '../../connection.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['items'=>[]]);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// obtener id_organizador
$stmtOrg = $conn->prepare('SELECT id_organizador FROM organizador WHERE id_usuario = ? LIMIT 1');
$stmtOrg->execute([$id_usuario]);
$org = $stmtOrg->fetch(PDO::FETCH_ASSOC);
$id_organizador = $org['id_organizador'] ?? null;

if (!$id_organizador) { echo json_encode(['items'=>[]]); exit; }

$stmt = $conn->prepare('SELECT st.id_solicitud_torneo, st.id_usuario, st.id_torneo, u.email, j.nombre as usuario_nombre, t.nombre as torneo_nombre FROM solicitud_torneo st LEFT JOIN usuario u ON st.id_usuario = u.id_usuario LEFT JOIN jugador j ON j.id_usuario = st.id_usuario LEFT JOIN torneo t ON t.id_torneo = st.id_torneo WHERE t.id_organizador = ? ORDER BY st.id_solicitud_torneo DESC LIMIT 50');
$stmt->execute([$id_organizador]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['items'=>$items]);

?>
