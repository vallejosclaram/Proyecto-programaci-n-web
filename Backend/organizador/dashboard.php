<?php
include '../../vistas/connection.php';

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit;
}
$id_usuario = $_SESSION['id_usuario'];

/* ====== OBTENER ID ORGANIZADOR ===== */
$sqlOrg = $conn->prepare("
    SELECT id_organizador 
    FROM organizador 
    WHERE id_usuario = ?
");
$sqlOrg->execute([$id_usuario]);
$org = $sqlOrg->fetch(PDO::FETCH_ASSOC);

if (!$org) {
    die("Error: el usuario no está registrado como organizador.");
}

$id_organizador = $org['id_organizador'];
/* ====== TORNEOS ACTIVOS DEL ORGANIZADOR ===== */
$sqlTorneos = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM torneo
    WHERE id_organizador = ? AND id_estado = 1
");
$sqlTorneos->execute([$id_organizador]);
$torneosActivos = $sqlTorneos->fetch(PDO::FETCH_ASSOC)['total'];

// EQUIPOS REGISTRADOS DEL ORGANIZADOR
$sql = "
  SELECT COUNT(DISTINCT te.id_equipo) AS total_equipos
  FROM torneo t
  INNER JOIN torneo_equipo te ON t.id_torneo = te.id_torneo
  WHERE t.id_organizador = ?
";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_organizador]);
$totalEquipos = $stmt->fetch(PDO::FETCH_ASSOC)['total_equipos'] ?? 0;

// SOLICITUDES PENDIENTES DEL ORGANIZADOR
$sql = "
    SELECT COUNT(*) AS total_pendientes
    FROM solicitud_torneo st
    INNER JOIN torneo t ON st.id_torneo = t.id_torneo
    WHERE t.id_organizador = :id_org
";
$stmt = $conn->prepare($sql);
$stmt->execute(['id_org' => $id_organizador]);
$solicitudesPendientes = $stmt->fetchColumn();


?>