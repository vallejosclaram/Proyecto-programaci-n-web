<?php
include __DIR__ . '/../../vistas/connection.php';
session_start();

// Verificar sesión
$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
  header('Location: ../../auth/login.php');
  exit;
}

// Obtener datos del organizador por id_usuario
$stmtOrg = $conn->prepare('SELECT * FROM organizador WHERE id_usuario = ? LIMIT 1');
$stmtOrg->execute([$id_usuario]);
$organizador = $stmtOrg->fetch(PDO::FETCH_ASSOC);

if (!$organizador) {
  echo "<p>No se encontró tu perfil de organizador.</p>";
  exit;
}

// Obtener email del usuario
$stmtUser = $conn->prepare('SELECT email FROM usuario WHERE id_usuario = ? LIMIT 1');
$stmtUser->execute([$id_usuario]);
$userRow = $stmtUser->fetch(PDO::FETCH_ASSOC);
$organizador['email'] = $userRow['email'] ?? null;

// Contar torneos creados por este organizador
$stmtCount = $conn->prepare('SELECT COUNT(*) as total FROM torneo WHERE id_organizador = ?');
$stmtCount->execute([$organizador['id_organizador']]);
$countRow = $stmtCount->fetch(PDO::FETCH_ASSOC);
$torneos_creados = $countRow['total'] ?? 0;

// Contar jugadores inscritos en todos sus torneos (torneo_jugador)
$stmtJug = $conn->prepare('SELECT COUNT(*) as inscritos FROM torneo_jugador tj JOIN torneo t ON tj.id_torneo = t.id_torneo WHERE t.id_organizador = ?');
$stmtJug->execute([$organizador['id_organizador']]);
$jugRow = $stmtJug->fetch(PDO::FETCH_ASSOC);
$jugadores_inscritos = $jugRow['inscritos'] ?? 0;

// Contar equipos inscritos en todos sus torneos (torneo_equipo)
$stmtEq = $conn->prepare("
    SELECT COUNT(DISTINCT te.id_equipo) AS equipos
    FROM torneo_equipo te
    INNER JOIN torneo t ON te.id_torneo = t.id_torneo
    WHERE t.id_organizador = ?
");
$stmtEq->execute([$organizador['id_organizador']]);
$equipos_inscritos = $stmtEq->fetchColumn();

/* ============================
   LISTA DE JUGADORES INSCRITOS
   ============================ */
$stmtJugList = $conn->prepare("
    SELECT 
        u.id_usuario,
        u.email,
        j.nombre,
        j.apellido,
        tj.fecha_inscripcion,
        t.nombre AS torneo
    FROM torneo_jugador tj
    INNER JOIN jugador j ON j.id_jugador = tj.id_jugador
    INNER JOIN usuario u ON u.id_usuario = j.id_usuario
    INNER JOIN torneo t ON t.id_torneo = tj.id_torneo
    WHERE t.id_organizador = :id_organizador
    ORDER BY tj.fecha_inscripcion DESC
");

$stmtJugList->execute([
    ":id_organizador" => $organizador['id_organizador']
]);

$listaJugadores = $stmtJugList->fetchAll(PDO::FETCH_ASSOC);



/* ============================
   LISTA DE EQUIPOS INSCRITOS
   ============================ */
$stmtEqList = $conn->prepare("
    SELECT 
        e.id_equipo,
        e.nombre,
        t.nombre AS torneo
    FROM torneo_equipo te
    INNER JOIN torneo t ON te.id_torneo = t.id_torneo
    INNER JOIN equipo e ON te.id_equipo = e.id_equipo
    WHERE t.id_organizador = ?
    ORDER BY t.nombre, e.nombre
");
$stmtEqList->execute([$organizador['id_organizador']]);
$listaEquipos = $stmtEqList->fetchAll(PDO::FETCH_ASSOC);


?>