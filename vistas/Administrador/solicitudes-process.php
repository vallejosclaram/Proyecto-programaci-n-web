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

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
if (!is_array($input)) {
    $input = [];
}
$accion = $input['accion'] ?? null;

if (!$accion) {
    // Listar solicitudes de creación
    $nombre_torneo = !empty($input['nombre_torneo']) ? trim($input['nombre_torneo']) : null;
    $nombre_juego  = !empty($input['nombre_juego']) ? trim($input['nombre_juego']) : null;
    $tipo_torneo   = !empty($input['tipo_torneo']) ? $input['tipo_torneo'] : null;
    $estado        = !empty($input['estado']) ? $input['estado'] : null;

    $sql = "SELECT sc.id_solicitud_creacion, sc.nombre, sc.descripcion,
                   sc.fecha_inicio, sc.fecha_fin, sc.fecha_solicitud,
                   u.email AS usuario, j.nombre AS juego, tt.descripcion AS tipo_torneo,
                   sc.estado
            FROM solicitud_creacion_torneo sc
            LEFT JOIN usuario u ON u.id_usuario = sc.id_usuario
            LEFT JOIN juego j ON j.id_juego = sc.id_juego
            LEFT JOIN tipo_torneo tt ON tt.id_tipo = sc.id_tipo
            WHERE 1=1";

    $params = [];

    if ($nombre_torneo) {
        $sql .= " AND sc.nombre LIKE ?";
        $params[] = "%$nombre_torneo%";
    }

    if ($nombre_juego) {
        $sql .= " AND j.nombre LIKE ?";
        $params[] = "%$nombre_juego%";
    }

    if ($tipo_torneo) {
        $sql .= " AND sc.id_tipo = ?";
        $params[] = $tipo_torneo;
    }

    if ($estado) {
        $sql .= " AND sc.estado = ?";
        $params[] = $estado;
    }

    $sql .= " ORDER BY sc.fecha_solicitud DESC";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'solicitudes' => $solicitudes
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Error en la consulta: '.$e->getMessage()]);
    }
    exit;
}

switch ($accion) {
    case 'aceptar_solicitud_creacion':
        $id_solicitud = $input['id_solicitud_creacion'] ?? null;
        if (!$id_solicitud) {
            echo json_encode(['success' => false, 'error' => 'Solicitud no especificada']);
            exit;
        }

        try {
            $conn->beginTransaction();

            // Obtener datos de la solicitud
            $stmt = $conn->prepare("SELECT * FROM solicitud_creacion_torneo WHERE id_solicitud_creacion = ? FOR UPDATE");
            $stmt->execute([$id_solicitud]);
            $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$solicitud) {
                $conn->rollBack();
                echo json_encode(['success' => false, 'error' => 'Solicitud inexistente']);
                exit;
            }

            if ($solicitud['estado'] !== 'pendiente') {
                $conn->rollBack();
                echo json_encode(['success' => false, 'error' => 'La solicitud ya fue procesada']);
                exit;
            }

            // Obtener organizador a partir del usuario de la solicitud
            $stmt = $conn->prepare("SELECT id_organizador FROM organizador WHERE id_usuario = ?");
            $stmt->execute([$solicitud['id_usuario']]);
            $organizador = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$organizador) {
                $conn->rollBack();
                echo json_encode(['success' => false, 'error' => 'El usuario no está registrado como organizador']);
                exit;
            }

            // Crear torneo a partir de la solicitud
            $stmt = $conn->prepare("INSERT INTO torneo (id_organizador, id_juego, nombre, descripcion, fecha_inicio, fecha_fin, id_estado, id_tipo)
                                    VALUES (?, ?, ?, ?, ?, ?, 1, ?)");
            $stmt->execute([
                $organizador['id_organizador'],
                $solicitud['id_juego'],
                $solicitud['nombre'],
                $solicitud['descripcion'],
                $solicitud['fecha_inicio'],
                $solicitud['fecha_fin'],
                $solicitud['id_tipo']
            ]);

            // Marcar solicitud como aprobada
            $stmt = $conn->prepare("UPDATE solicitud_creacion_torneo SET estado = 'aprobado' WHERE id_solicitud_creacion = ?");
            $stmt->execute([$id_solicitud]);

            $conn->commit();

            echo json_encode(['success' => true, 'message' => 'Solicitud aprobada y torneo creado']);
        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            echo json_encode(['success' => false, 'error' => 'Error al aprobar solicitud: '.$e->getMessage()]);
        }
        break;

    case 'rechazar_solicitud_creacion':
        $id_solicitud = $input['id_solicitud_creacion'] ?? null;
        if (!$id_solicitud) {
            echo json_encode(['success' => false, 'error' => 'Solicitud no especificada']);
            exit;
        }

        try {
            $stmt = $conn->prepare("SELECT estado FROM solicitud_creacion_torneo WHERE id_solicitud_creacion = ?");
            $stmt->execute([$id_solicitud]);
            $estado = $stmt->fetchColumn();

            if (!$estado) {
                echo json_encode(['success' => false, 'error' => 'Solicitud inexistente']);
                exit;
            }

            if ($estado !== 'pendiente') {
                echo json_encode(['success' => false, 'error' => 'La solicitud ya fue procesada']);
                exit;
            }

            // Marcar solicitud como rechazada
            $stmt = $conn->prepare("UPDATE solicitud_creacion_torneo SET estado = 'rechazado' WHERE id_solicitud_creacion = ?");
            $stmt->execute([$id_solicitud]);

            echo json_encode(['success' => true, 'message' => 'Solicitud rechazada']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error al rechazar solicitud: '.$e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acción desconocida']);
}
