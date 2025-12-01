<?php
session_start();
include __DIR__ . '/../../vistas/connection.php';
header('Content-Type: application/json');

$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$tipo = $_GET['tipo'] ?? 'equipo';
$juego = $_GET['juego'] ?? null;
$nombre = $_GET['nombre'] ?? null;

function imagenJuego($val) {
    $base = "http://localhost/Proyecto-programaci-n-web/vistas/Organizador/img/";
    if (is_numeric($val)) {
        $id = (int)$val;
        if ($id === 1) return $base . "valorant.jpg";
        if ($id === 2) return $base . "Counter-Strike.jpg";
        return $base . "default.png";
    }
    $j = strtolower((string)$val);
    if (strpos($j, "valorant") !== false) return $base . "valorant.jpg";
    if (strpos($j, "counter") !== false) return $base . "Counter-Strike.jpg";
    return $base . "default.png";
}

try {
    $params = [];

    if ($tipo === 'jugador') {
        $sql = "
            SELECT
              j.id_jugador,
              TRIM(CONCAT(COALESCE(j.nombre,''),' ',COALESCE(j.apellido,''))) AS nombre,
              COALESCE(SUM(pt.puntaje_obtenido), j.puntaje, 0) AS puntaje,
              g.id_juego AS id_juego,
              g.nombre AS juego
            FROM jugador j
            LEFT JOIN torneo_jugador tj ON tj.id_jugador = j.id_jugador
            LEFT JOIN torneo t ON t.id_torneo = tj.id_torneo
            LEFT JOIN juego g ON g.id_juego = t.id_juego
            LEFT JOIN puntaje_torneo pt ON pt.id_jugador = j.id_jugador AND pt.id_torneo = t.id_torneo
            WHERE 1=1
        ";

        if ($juego) {
            $sql .= " AND g.nombre LIKE ? ";
            $params[] = "%$juego%";
        }
        if ($nombre) {
            $sql .= " AND (j.nombre LIKE ? OR j.apellido LIKE ?) ";
            $params[] = "%$nombre%";
            $params[] = "%$nombre%";
        }

        // agrupar por id_jugador y por identificador/nombre del juego para evitar errores de ONLY_FULL_GROUP_BY
        $sql .= " GROUP BY j.id_jugador, g.id_juego, g.nombre
                  ORDER BY puntaje DESC
                  LIMIT 500
        ";

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
                'id_juego' => isset($r['id_juego']) ? (int)$r['id_juego'] : null,
                'imagen' => imagenJuego($r['id_juego']),
            ];
        }

        echo json_encode(['data' => $out], JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        // equipos
        $sql = "
            SELECT
              e.id_equipo,
              e.nombre,
              COALESCE(SUM(pt.puntaje_obtenido),0) AS puntaje,
              g.id_juego AS id_juego,
              g.nombre AS juego
            FROM equipo e
            LEFT JOIN torneo_equipo te ON te.id_equipo = e.id_equipo
            LEFT JOIN torneo t ON t.id_torneo = te.id_torneo
            LEFT JOIN juego g ON g.id_juego = t.id_juego
            LEFT JOIN puntaje_torneo pt ON pt.id_equipo = e.id_equipo AND pt.id_torneo = t.id_torneo
            WHERE 1=1
        ";

        if ($juego) {
            $sql .= " AND g.nombre LIKE ? ";
            $params[] = "%$juego%";
        }
        if ($nombre) {
            $sql .= " AND e.nombre LIKE ? ";
            $params[] = "%$nombre%";
        }

        $sql .= " GROUP BY e.id_equipo, g.id_juego, g.nombre
                  ORDER BY puntaje DESC
                  LIMIT 500
        ";

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
                'id_juego' => isset($r['id_juego']) ? (int)$r['id_juego'] : null,
                'imagen' => imagenJuego($r['id_juego']),
            ];
        }

        echo json_encode(['data' => $out], JSON_UNESCAPED_UNICODE);
        exit;
    }
} catch (Exception $e) {
    // devolver error para debugging (quitar mensaje detallado en producción)
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
