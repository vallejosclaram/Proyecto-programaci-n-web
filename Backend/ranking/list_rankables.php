<?php
session_start();
include '../../vistas/connection.php';
header('Content-Type: application/json');
$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
    header('Location: ../../vistas/auth/login.php');
    exit;
}

$tipo = $_GET['tipo'] ?? 'equipo'; // 'equipo' o 'jugador'
$juego = $_GET['juego'] ?? null; // nombre del juego (opcional)
$nombre = $_GET['nombre'] ?? null; // busqueda por nombre (opcional)

function imagenJuegoPath($juego) {
  $base = '/Proyecto-programaci-n-web/vistas/Organizador/img/';
  $j = strtolower((string)$juego);
  if (strpos($j, 'valorant') !== false) return $base . 'valorant.jpg';
  if (strpos($j, 'counter') !== false) return $base . 'Counter-Strike.jpg';
  return $base . 'default.png';
}

try {
  if ($tipo === 'jugador') {
    $sql = "SELECT j.id_jugador, TRIM(CONCAT(COALESCE(j.nombre,''),' ',COALESCE(j.apellido,''))) AS nombre, COALESCE(SUM(pt.puntaje_obtenido), j.puntaje, 0) AS puntaje, ju.nombre AS juego
      FROM jugador j
      LEFT JOIN puntaje_torneo pt ON pt.id_jugador = j.id_jugador
      LEFT JOIN torneo t ON pt.id_torneo = t.id_torneo
      LEFT JOIN juego ju ON t.id_juego = ju.id_juego
      WHERE 1=1";
    $params = [];
    if ($juego) { $sql .= " AND ju.nombre LIKE ?"; $params[] = "%$juego%"; }
    if ($nombre) { $sql .= " AND (j.nombre LIKE ? OR j.apellido LIKE ?)"; $params[] = "%$nombre%"; $params[] = "%$nombre%"; }
    $sql .= " GROUP BY j.id_jugador ORDER BY puntaje DESC LIMIT 500";
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out = [];
    foreach ($rows as $r) {
      $out[] = [
        'tipo' => 'jugador',
        'id' => $r['id_jugador'],
        'nombre' => $r['nombre'] ?: ('Jugador #' . $r['id_jugador']),
        'puntaje' => (int)$r['puntaje'],
        'juego' => $r['juego'],
        'imagen' => imagenJuegoPath($r['juego']),
      ];
    }
    echo json_encode(['data' => $out]);
    exit;
  } else {
    // equipos
    $sql = "SELECT e.id_equipo, e.nombre, COALESCE(SUM(pt.puntaje_obtenido),0) AS puntaje, ju.nombre AS juego
      FROM equipo e
      LEFT JOIN puntaje_torneo pt ON pt.id_equipo = e.id_equipo
      LEFT JOIN torneo t ON pt.id_torneo = t.id_torneo
      LEFT JOIN juego ju ON t.id_juego = ju.id_juego
      WHERE 1=1";
    $params = [];
    if ($juego) { $sql .= " AND ju.nombre LIKE ?"; $params[] = "%$juego%"; }
    if ($nombre) { $sql .= " AND e.nombre LIKE ?"; $params[] = "%$nombre%"; }
    $sql .= " GROUP BY e.id_equipo ORDER BY puntaje DESC LIMIT 500";
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out = [];
    foreach ($rows as $r) {
      $out[] = [
        'tipo' => 'equipo',
        'id' => $r['id_equipo'],
        'nombre' => $r['nombre'],
        'puntaje' => (int)$r['puntaje'],
        'juego' => $r['juego'],
        'imagen' => imagenJuegoPath($r['juego']),
      ];
    }
    echo json_encode(['data' => $out]);
    exit;
  }
} catch (Exception $e) {
  echo json_encode(['error' => $e->getMessage()]);
  exit;
}

?>
