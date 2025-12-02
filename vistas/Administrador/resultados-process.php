<?php
session_start();
require_once(__DIR__ . '/../connection.php');
require_once(__DIR__ . '/../includes/clases/permisos.php');

header('Content-Type: application/json; charset=UTF-8');

// Validar sesión y rol
if (empty($_SESSION['admin']['id'])) {
    echo json_encode(['success' => false, 'error' => 'No hay usuario administrador logueado']);
    exit;
}
$id_admin = $_SESSION['admin']['id'];
$rol = $_SESSION['admin']['rol'] ?? null;

if ($rol != 1) {
    echo json_encode(['success' => false, 'error' => 'Acceso restringido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$accion = $input['accion'] ?? null;

if (!$accion) {
    // Filtros opcionales
    $id_juego      = !empty($input['id_juego']) ? $input['id_juego'] : null;
    $id_torneo     = !empty($input['id_torneo']) ? $input['id_torneo'] : null;
    $tipo_torneo   = !empty($input['tipo_torneo']) ? $input['tipo_torneo'] : null;
    $nombre_torneo = !empty($input['nombre_torneo']) && trim($input['nombre_torneo']) !== '' ? trim($input['nombre_torneo']) : null;
    $nombre_juego  = !empty($input['nombre_juego']) && trim($input['nombre_juego']) !== '' ? trim($input['nombre_juego']) : null;

    $sql = "SELECT p.id_puntaje, p.id_torneo, p.id_jugador, p.id_equipo,
                   p.puntaje_obtenido, p.fecha_registro, p.estado_validacion,
                   u.email AS jugador, e.nombre AS equipo,
                   t.nombre AS torneo, j.nombre AS juego,
                   tt.descripcion AS tipo_torneo
            FROM puntaje_torneo p
            LEFT JOIN usuario u ON u.id_usuario = p.id_jugador
            LEFT JOIN equipo e ON e.id_equipo = p.id_equipo
            LEFT JOIN torneo t ON t.id_torneo = p.id_torneo
            LEFT JOIN juego j ON j.id_juego = t.id_juego
            LEFT JOIN tipo_torneo tt ON tt.id_tipo = t.id_tipo
            WHERE 1=1";

    $params = [];

    if ($id_juego) {
        $sql .= " AND j.id_juego = ?";
        $params[] = $id_juego;
    }
    if ($id_torneo) {
        $sql .= " AND t.id_torneo = ?";
        $params[] = $id_torneo;
    }
    if ($tipo_torneo) {
        $sql .= " AND t.id_tipo = ?";
        $params[] = $tipo_torneo;
    }
    if ($nombre_torneo) {
        $sql .= " AND t.nombre LIKE ?";
        $params[] = "%$nombre_torneo%";
    }
    if ($nombre_juego) {
        $sql .= " AND j.nombre LIKE ?";
        $params[] = "%$nombre_juego%";
    }

    $sql .= " ORDER BY p.fecha_registro DESC";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'resultados' => $resultados
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Error en la consulta: '.$e->getMessage()]);
    }
    exit;
}

switch ($accion) {
    case 'aceptar_resultado':
        if (!Permisos::tienePermiso('gestionar_puntaje', $id_admin)) {
            echo json_encode(['success' => false, 'error' => 'No tenés permiso para aceptar resultados']);
            exit;
        }
        $id_puntaje = $input['id_puntaje'] ?? null;
        if (!$id_puntaje) {
            echo json_encode(['success' => false, 'error' => 'Resultado no especificado']);
            exit;
        }

        try {
            $stmt = $conn->prepare("UPDATE puntaje_torneo 
                                    SET estado_validacion = 'aprobado' 
                                    WHERE id_puntaje = ?");
            $stmt->execute([$id_puntaje]);

            $stmt = $conn->prepare("INSERT INTO validacion_puntaje (id_puntaje, id_admin, resultado) 
                                    VALUES (?, ?, 'aprobado')");
            $stmt->execute([$id_puntaje, $id_admin]);

            echo json_encode(['success' => true, 'message' => 'Resultado aceptado y rankings actualizados']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error al aceptar resultado: '.$e->getMessage()]);
        }
        break;

    case 'rechazar_resultado':
        if (!Permisos::tienePermiso('gestionar_puntaje', $id_admin)) {
            echo json_encode(['success' => false, 'error' => 'No tenés permiso para rechazar resultados']);
            exit;
        }
        $id_puntaje = $input['id_puntaje'] ?? null;
        if (!$id_puntaje) {
            echo json_encode(['success' => false, 'error' => 'Resultado no especificado']);
            exit;
        }

        try {
            $stmt = $conn->prepare("UPDATE puntaje_torneo 
                                    SET estado_validacion = 'rechazado' 
                                    WHERE id_puntaje = ?");
            $stmt->execute([$id_puntaje]);

            $stmt = $conn->prepare("INSERT INTO validacion_puntaje (id_puntaje, id_admin, resultado) 
                                    VALUES (?, ?, 'rechazado')");
            $stmt->execute([$id_puntaje, $id_admin]);

            echo json_encode(['success' => true, 'message' => 'Resultado rechazado']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error al rechazar resultado: '.$e->getMessage()]);
        }
        break;

    case 'obtener_ranking':
        $nombre_juego = !empty($input['nombre_juego']) && trim($input['nombre_juego']) !== '' ? trim($input['nombre_juego']) : null;
        $tipo = !empty($input['tipo']) ? $input['tipo'] : null;

        try {
            // Ranking de jugadores individuales
            $sql_individual = "SELECT u.email AS nombre, j.nombre AS juego, 
                                      SUM(p.puntaje_obtenido) AS puntos_totales
                               FROM puntaje_torneo p
                               INNER JOIN usuario u ON u.id_usuario = p.id_jugador
                               INNER JOIN torneo t ON t.id_torneo = p.id_torneo
                               INNER JOIN juego j ON j.id_juego = t.id_juego
                               WHERE p.estado_validacion = 'aprobado' 
                               AND p.id_jugador IS NOT NULL
                               AND p.id_equipo IS NULL";
            
            $params_individual = [];
            
            if ($nombre_juego) {
                $sql_individual .= " AND j.nombre LIKE ?";
                $params_individual[] = "%$nombre_juego%";
            }
            
            $sql_individual .= " GROUP BY u.id_usuario, j.id_juego, u.email, j.nombre
                                 ORDER BY puntos_totales DESC
                                 LIMIT 50";

            // Ranking de equipos
            $sql_equipo = "SELECT e.nombre AS nombre, j.nombre AS juego,
                                  SUM(p.puntaje_obtenido) AS puntos_totales
                           FROM puntaje_torneo p
                           INNER JOIN equipo e ON e.id_equipo = p.id_equipo
                           INNER JOIN torneo t ON t.id_torneo = p.id_torneo
                           INNER JOIN juego j ON j.id_juego = t.id_juego
                           WHERE p.estado_validacion = 'aprobado'
                           AND p.id_equipo IS NOT NULL";
            
            $params_equipo = [];
            
            if ($nombre_juego) {
                $sql_equipo .= " AND j.nombre LIKE ?";
                $params_equipo[] = "%$nombre_juego%";
            }
            
            $sql_equipo .= " GROUP BY e.id_equipo, j.id_juego, e.nombre, j.nombre
                            ORDER BY puntos_totales DESC
                            LIMIT 50";

            $ranking = [];

            // Obtener ranking individual si no se filtra por tipo o si es individual
            if (!$tipo || $tipo === 'individual') {
                $stmt = $conn->prepare($sql_individual);
                $stmt->execute($params_individual);
                $individuales = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $ranking = array_merge($ranking, $individuales);
            }

            // Obtener ranking de equipos si no se filtra por tipo o si es equipo
            if (!$tipo || $tipo === 'equipo') {
                $stmt = $conn->prepare($sql_equipo);
                $stmt->execute($params_equipo);
                $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $ranking = array_merge($ranking, $equipos);
            }

            // Ordenar por puntos totales descendente
            usort($ranking, function($a, $b) {
                return $b['puntos_totales'] - $a['puntos_totales'];
            });

            echo json_encode([
                'success' => true,
                'ranking' => array_slice($ranking, 0, 50) // Top 50
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error al obtener ranking: '.$e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acción desconocida']);
}
