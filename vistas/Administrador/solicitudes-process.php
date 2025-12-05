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

if (!Permisos::tienePermiso('solicitar_creacion_torneo', $id_admin)) {
    echo json_encode(['success' => false, 'error' => 'No tenés permiso para gestionar solicitudes de creación de torneos']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$accion = $input['accion'] ?? null;

if (!$accion) {
    $juego = $_GET['juego'] ?? '';
    $tipo = $_GET['tipo'] ?? '';

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

    if ($juego !== '') {
        $sql .= " AND j.nombre = ?";
        $params[] = $juego;
    }
    if ($tipo !== '') {
        $sql .= " AND sc.id_tipo = ?";
        $params[] = $tipo;
    }

    $sql .= " ORDER BY sc.fecha_solicitud DESC";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'solicitudes' => $solicitudes]);
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

            $stmt = $conn->prepare("SELECT * FROM solicitud_creacion_torneo WHERE id_solicitud_creacion = ?");
            $stmt->execute([$id_solicitud]);
            $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$solicitud) {
                $conn->rollBack();
                echo json_encode(['success' => false, 'error' => 'Solicitud no encontrada']);
                exit;
            }

            $stmt = $conn->prepare("SELECT id_organizador FROM organizador WHERE id_usuario = ?");
            $stmt->execute([$solicitud['id_usuario']]);
            $org = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$org) {
                $stmt = $conn->prepare("INSERT INTO organizador (id_usuario, nombre, id_estado) VALUES (?, 'Organizador', 1)");
                $stmt->execute([$solicitud['id_usuario']]);
                $id_organizador = $conn->lastInsertId();
            } else {
                $id_organizador = $org['id_organizador'];
            }

            $stmt = $conn->prepare("INSERT INTO torneo 
                (id_organizador, id_juego, nombre, descripcion, fecha_inicio, fecha_fin, id_estado, id_tipo) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $id_organizador,
                $solicitud['id_juego'],
                $solicitud['nombre'],
                $solicitud['descripcion'],
                $solicitud['fecha_inicio'],
                $solicitud['fecha_fin'],
                1, 
                $solicitud['id_tipo']
            ]);

            $stmt = $conn->prepare("DELETE FROM solicitud_creacion_torneo WHERE id_solicitud_creacion = ?");
            $stmt->execute([$id_solicitud]);

            $conn->commit();

            echo json_encode(['success' => true, 'message' => 'Solicitud aprobada y torneo creado']);
        } catch (PDOException $e) {
            if ($conn->inTransaction()) $conn->rollBack();
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
            $stmt = $conn->prepare("DELETE FROM solicitud_creacion_torneo WHERE id_solicitud_creacion = ?");
            $stmt->execute([$id_solicitud]);

            echo json_encode(['success' => true, 'message' => 'Solicitud rechazada y eliminada']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error al rechazar solicitud: '.$e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acción desconocida']);
}
