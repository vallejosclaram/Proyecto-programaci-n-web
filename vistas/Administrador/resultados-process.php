<?php
session_start();
require_once(__DIR__ . '/../connection.php');
require_once(__DIR__ . '/../includes/clases/permisos.php');

header('Content-Type: application/json; charset=UTF-8');

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
$accion = $input['accion'] ?? '';

if (empty($accion)) {
    echo json_encode(['success' => false, 'error' => 'Acción no especificada']);
    exit;
}

const PUNTOS_VICTORIA = 3;
const PUNTOS_EMPATE = 1;
const PUNTOS_DERROTA = 0;

switch ($accion) {

    case 'obtener_pendientes':
        try {
            $sql = "
                SELECT 
                    p.id_partida, p.ronda, p.tipo, p.id_torneo, p.estado AS estado_partida,
                    U1.email AS jugador1_email, U2.email AS jugador2_email, 
                    E1.nombre AS equipo1_nombre, E2.nombre AS equipo2_nombre,
                    rr.resultado_a, rr.resultado_b,
                    U_PROP.email AS propone_usuario,
                    rr.id_reporte
                FROM partida p
                JOIN reporte_resultado rr ON rr.id_partida = p.id_partida 
                LEFT JOIN usuario U1 ON U1.id_usuario = p.id_jugador1
                LEFT JOIN usuario U2 ON U2.id_usuario = p.id_jugador2
                LEFT JOIN equipo E1 ON E1.id_equipo = p.id_equipo1
                LEFT JOIN equipo E2 ON E2.id_equipo = p.id_equipo2
                LEFT JOIN usuario U_PROP ON U_PROP.id_usuario = rr.id_usuario 
                WHERE rr.estado = 'reportado'
                AND p.estado != 'finalizada'
                AND NOT EXISTS (SELECT 1 FROM resolucion_admin ra WHERE ra.id_partida = p.id_partida)
                ORDER BY p.id_partida DESC
            ";

            $stmt = $conn->query($sql);
            $partidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $partidas_formateadas = array_map(function($p) {
                $p['resultado_propuesto'] = $p['resultado_a'] . '-' . $p['resultado_b'];
                $p['propone_usuario'] = $p['propone_usuario'] ?? 'Usuario Desconocido';
                return $p;
            }, $partidas);

            echo json_encode(['success' => true, 'partidas' => $partidas_formateadas]);

        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error SQL al obtener pendientes: ' . $e->getMessage()]);
        }
        break;

    case 'aceptar_resultado':
        $id_partida = $input['id_partida'] ?? null;
        $resultado = $input['resultado'] ?? null;

        if (!$id_partida || !$resultado) {
            echo json_encode(['success' => false, 'error' => 'ID de partida o resultado no especificado']);
            exit;
        }

        list($res_a, $res_b) = explode('-', $resultado);
        $res_a = (int)$res_a;
        $res_b = (int)$res_b;

        try {
            $conn->beginTransaction();

            $sql_partida = "SELECT id_torneo, id_jugador1, id_jugador2, id_equipo1, id_equipo2, tipo FROM partida WHERE id_partida = ?";
            $stmt_partida = $conn->prepare($sql_partida);
            $stmt_partida->execute([$id_partida]);
            $partida = $stmt_partida->fetch(PDO::FETCH_ASSOC);

            if (!$partida) {
                throw new Exception("Partida no encontrada.");
            }

            $sql_insert_res = "
                INSERT INTO resolucion_admin (id_partida, id_admin, resultado_final_a, resultado_final_b, observaciones)
                VALUES (?, ?, ?, ?, 'Resultado de reporte aceptado.')
            ";
            $stmt_insert_res = $conn->prepare($sql_insert_res);
            $stmt_insert_res->execute([$id_partida, $id_admin, $res_a, $res_b]);

            $puntos_p1 = 0;
            $puntos_p2 = 0;

            if ($res_a > $res_b) {
                $puntos_p1 = PUNTOS_VICTORIA;
                $puntos_p2 = PUNTOS_DERROTA;
            } elseif ($res_a < $res_b) {
                $puntos_p1 = PUNTOS_DERROTA;
                $puntos_p2 = PUNTOS_VICTORIA;
            } else {
                $puntos_p1 = PUNTOS_EMPATE;
                $puntos_p2 = PUNTOS_EMPATE;
            }

            $id_entidad1 = $partida['tipo'] === 'individual' ? $partida['id_jugador1'] : $partida['id_equipo1'];
            $campo_entidad1 = $partida['tipo'] === 'individual' ? 'id_jugador' : 'id_equipo';

            $id_entidad2 = $partida['tipo'] === 'individual' ? $partida['id_jugador2'] : $partida['id_equipo2'];
            $campo_entidad2 = $partida['tipo'] === 'individual' ? 'id_jugador' : 'id_equipo';

            $insertar_puntaje = function($id_entidad, $campo_entidad, $puntos) use ($conn, $id_torneo, $id_partida) {
                $sql = "
                    INSERT INTO puntaje_torneo (id_torneo, {$campo_entidad}, puntaje_obtenido, id_partida, estado_validacion)
                    VALUES (:id_torneo, :id_entidad, :puntos, :id_partida, 'aprobado')
                ";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':id_torneo' => $id_torneo,
                    ':id_entidad' => $id_entidad,
                    ':puntos' => $puntos,
                    ':id_partida' => $id_partida
                ]);
            };

            $insertar_puntaje($id_entidad1, $campo_entidad1, $puntos_p1);
            $insertar_puntaje($id_entidad2, $campo_entidad2, $puntos_p2);

            $sql_update_partida = "UPDATE partida SET estado = 'finalizada' WHERE id_partida = ?";
            $stmt_update_partida = $conn->prepare($sql_update_partida);
            $stmt_update_partida->execute([$id_partida]);

            $sql_update_reporte = "UPDATE reporte_resultado SET estado = 'validado_admin' WHERE id_partida = ? AND estado = 'reportado'";
            $stmt_update_reporte = $conn->prepare($sql_update_reporte);
            $stmt_update_reporte->execute([$id_partida]);

            $conn->commit();

            echo json_encode(['success' => true, 'message' => 'Resultado aceptado, resolución registrada y partida finalizada.']);

        } catch (Exception $e) {
            $conn->rollBack();
            echo json_encode(['success' => false, 'error' => 'Error al aceptar resultado: ' . $e->getMessage()]);
        }
        break;

    case 'rechazar_resultado':
        $id_partida = $input['id_partida'] ?? null;
        $observaciones = 'Resultado de reporte rechazado por el administrador.';

        if (!$id_partida) {
            echo json_encode(['success' => false, 'error' => 'ID de partida no especificado']);
            exit;
        }

        try {
            $conn->beginTransaction();

            $sql_insert_res = "
                INSERT INTO resolucion_admin (id_partida, id_admin, resultado_final_a, resultado_final_b, observaciones)
                VALUES (?, ?, 0, 0, ?)
            ";
            $stmt_insert_res = $conn->prepare($sql_insert_res);
            $stmt_insert_res->execute([$id_partida, $id_admin, $observaciones]);

            $sql_revert_partida = "UPDATE partida SET estado = 'en_progreso' WHERE id_partida = ?";
            $stmt_revert = $conn->prepare($sql_revert_partida);
            $stmt_revert->execute([$id_partida]);

            $sql_update_reporte = "UPDATE reporte_resultado SET estado = 'validado_admin' WHERE id_partida = ? AND estado = 'reportado'";
            $stmt_update_reporte = $conn->prepare($sql_update_reporte);
            $stmt_update_reporte->execute([$id_partida]);

            $conn->commit();

            echo json_encode(['success' => true, 'message' => 'Resultado rechazado.']);

        } catch (PDOException $e) {
            $conn->rollBack();
            echo json_encode(['success' => false, 'error' => 'Error al rechazar resultado: ' . $e->getMessage()]);
        }
        break;

    case 'obtener_ranking':
        $id_torneo = $input['id_torneo'] ?? null;
        $tipo_ranking = $input['tipo'] ?? 'individual';

        if (!$id_torneo) {
            echo json_encode(['success' => false, 'error' => 'ID de torneo no especificado para ranking']);
            exit;
        }

        try {
            if ($tipo_ranking === 'equipo') {
                $campo_entidad = 'id_equipo';
                $join_tabla = 'equipo';
                $campo_nombre = 'nombre';
            } else {
                $campo_entidad = 'id_jugador';
                $join_tabla = 'usuario';
                $campo_nombre = 'email';
            }

            $sql = "
                SELECT 
                    pt.{$campo_entidad} AS id_entidad,
                    E.{$campo_nombre} AS nombre_entidad,
                    SUM(pt.puntaje_obtenido) AS puntos,
                    SUM(CASE WHEN pt.puntaje_obtenido = :p_v THEN 1 ELSE 0 END) AS victorias,
                    SUM(CASE WHEN pt.puntaje_obtenido = :p_e THEN 1 ELSE 0 END) AS empates,
                    SUM(CASE WHEN pt.puntaje_obtenido = :p_d THEN 1 ELSE 0 END) AS derrotas
                FROM puntaje_torneo pt
                JOIN {$join_tabla} E ON E.id_{$campo_entidad} = pt.{$campo_entidad}
                WHERE pt.id_torneo = :id_torneo 
                AND pt.estado_validacion = 'aprobado'
                AND pt.{$campo_entidad} IS NOT NULL
                GROUP BY pt.{$campo_entidad}, E.{$campo_nombre}
                ORDER BY puntos DESC, victorias DESC, derrotas ASC
            ";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id_torneo', $id_torneo, PDO::PARAM_INT);
            $stmt->bindValue(':p_v', PUNTOS_VICTORIA, PDO::PARAM_INT);
            $stmt->bindValue(':p_e', PUNTOS_EMPATE, PDO::PARAM_INT);
            $stmt->bindValue(':p_d', PUNTOS_DERROTA, PDO::PARAM_INT);

            $stmt->execute();
            $ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'ranking' => $ranking]);

        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error al obtener ranking: ' . $e->getMessage()]);
        }
        break;

    case 'obtener_bracket':
        $id_torneo = $input['id_torneo'] ?? null;

        if (!$id_torneo) {
            echo json_encode(['success' => false, 'error' => 'ID de torneo no especificado']);
            exit;
        }

        try {
            $sql = "
                SELECT 
                    p.id_partida, p.ronda, p.estado, p.tipo, p.fecha,
                    RA.resultado_final_a, RA.resultado_final_b,
                    U1.email AS jugador1, U2.email AS jugador2,
                    E1.nombre AS equipo1, E2.nombre AS equipo2
                FROM partida p
                LEFT JOIN usuario U1 ON U1.id_usuario = p.id_jugador1
                LEFT JOIN usuario U2 ON U2.id_usuario = p.id_jugador2
                LEFT JOIN equipo E1 ON E1.id_equipo = p.id_equipo1
                LEFT JOIN equipo E2 ON E2.id_equipo = p.id_equipo2
                LEFT JOIN resolucion_admin RA ON RA.id_partida = p.id_partida
                WHERE p.id_torneo = ?
                ORDER BY p.ronda ASC, p.fecha ASC
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$id_torneo]);
            $bracket = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $bracket_formateado = array_map(function($p) {
                if ($p['resultado_final_a'] !== null) {
                    $p['resultado_final'] = $p['resultado_final_a'] . '-' . $p['resultado_final_b'];
                } else {
                    $p['resultado_final'] = null;
                }
                unset($p['resultado_final_a'], $p['resultado_final_b']);
                return $p;
            }, $bracket);

            echo json_encode(['success' => true, 'bracket' => $bracket_formateado]);

        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error al obtener el bracket: ' . $e->getMessage()]);
        }
        break;

    case 'listar_torneos':
        try {
            $sql = "SELECT id_torneo, nombre FROM torneo ORDER BY id_torneo DESC";
            $stmt = $conn->query($sql);
            $torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'torneos' => $torneos]);

        } catch (PDOException $e) {
            echo json_encode(['success' => true, 'torneos' => [
                ['id_torneo' => 998, 'nombre' => 'Torneo Individual Prueba'],
                ['id_torneo' => 999, 'nombre' => 'Torneo de Equipos Final']
            ]]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acción desconocida']);
}
?>
