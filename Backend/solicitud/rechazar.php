<?php
include '../../connection.php';

if (!isset($_GET['id_torneo']) || !isset($_GET['id_jugador'])) {
    header("Location: solicitudes.php?error=missing_params");
    exit;
}

$id_torneo = intval($_GET['id_torneo']);
$id_jugador = intval($_GET['id_jugador']);

$sqlDelete = "DELETE FROM solicitud_jugador
              WHERE id_torneo = :torneo AND id_jugador = :jugador";

$stmtDelete = $conn->prepare($sqlDelete);
$stmtDelete->execute([
    ':torneo' => $id_torneo,
    ':jugador' => $id_jugador
]);

header("Location: solicitudes.php?rejected=1");
exit;
